<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\Bande;
use App\Models\Ferme;
use App\Models\Unite;
use App\Models\Produit;
use App\Models\Arrivage;
use App\Models\Categorie;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArrivageProduit;

class ArrivageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle      = "Gestion des arrivages abattoirs";
        $fermes = Ferme::get();
        $bandes = Bande::latest('id')
                                ->when(request()->ferme, function ($query, $ferme) {
                                    $query->where('ferme_id',$ferme);
                                })
                                ->with('ferme')
                                ->get();
        $arrivages = Arrivage::joinRelationship('bande')
                                ->joinRelationship('bande.ferme')
                                ->searchable(['numero_bande','fermes.nom'])
                                ->when(request()->ferme, function ($query, $ferme) {
                                    $query->where('ferme_id',$ferme); 
                                })
                                ->when(request()->bande, function ($query, $bande) {
                                    $query->where('bande_id',$bande);
                                })
                                ->when(request()->date, function ($query, $date) {
                                    $date      = explode('-', request()->date); 
                                    $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d'); 
                                    $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
                                    request()->merge(['start_date' => $startDate, 'end_date' => $endDate]); 
                                    request()->validate([
                                        'start_date' => 'required|date_format:Y-m-d',
                                        'end_date'   => 'nullable|date_format:Y-m-d',
                                    ]);
                                    $query->whereDate('date_arrivage', '>=', $startDate)->whereDate('date_arrivage', '<=', $endDate);
                                })
                                ->latest('id')
                                ->with('bande')
                                ->paginate(getPaginate());
        return view('admin.arrivage.index', compact('pageTitle','bandes','fermes','arrivages'));
    }
    public function decoupe($id)
    {
 
        $decoupes = ArrivageProduit::where([['niveau',1],['arrivage_id',$id]])->paginate(getPaginate());
        $arrivage = Arrivage::where('id', $id)->first();
        $pageTitle = "Gestion des découpes pour Arrivage N° ".$arrivage->bande->numero_bande;
        return view('admin.arrivage.decoupe', compact('pageTitle','decoupes','id'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Ajouter un arrivage";
        $fermes = Ferme::get(); 
        $categories = Categorie::where('niveau',0)->with('unite')->get();
        return view('admin.arrivage.create', compact('pageTitle','fermes','categories'));
    }

    public function createDecoupe($id)
    {
        
        $categoriePoulets = ArrivageProduit::where([['niveau',0],['arrivage_id',$id]])->get();
        
        $categories = Categorie::where('niveau',1)->get();
        $arrivage = Arrivage::where('id', $id)->first();
        $pageTitle = "Ajouter des découpes pour Arrivage N° ".$arrivage->bande->numero_bande;
        return view('admin.arrivage.create-decoupe', compact('pageTitle','categoriePoulets','categories','id'));
    }

    public function getBande()
    { 
        $id = request()->ferme;
        $bandes = Bande::where('ferme_id', $id)->get();
        if ($bandes->count()) {
            $contents = '<option value=""></option>';

            foreach ($bandes as $data) {
                $poussins = $data->nombre_poussins;
                $total = Arrivage::where('bande_id',$data->id)->sum('total_poulet');
                if($total !=null){
                    if($total>=$data->nombre_poussins){
                        continue;
                    }
                    $poussins = $data->nombre_poussins - $total;
                }
                $contents .= '<option value="' . $data->numero_bande . '" data-qte="'.$poussins.'">' . $data->numero_bande . '</option>';
            }
        } else {
            $contents = null;
        }

        return $contents;
    }

    public function verifyQuantity()
    { 
        $bande = request()->bande;
        $bandes = Bande::where('id', $bande)->first();
        if ($bandes !=null) {
            $nbPoussins = $bandes->nombre_poussins; 
        } else {
            $contents = null;
        }

        return $contents;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validationRule = [
            'total' => 'required', 
            'bande' => 'required', 
            'date_arrivage' => 'required', 
            'unite' => 'required|array',
            'categorie' => 'required|array',
            'price' => 'required|array',  
            'quantite' => 'required|array'   
        ];
        $request->validate($validationRule);
        $total_dispatch = array_sum($request->quantite);
 
        if ($total_dispatch != $request->total) {
            $notify[] = ['error', 'Le total de poulets arrivé doit être égale au total de poulets reparti selon leur catégorie. Veuillez apporter des modification avant de soumettre le formatulaire.'];
            return back()->withNotify($notify)->withInput();
        }

        if($request->id) {
            $arrivage = Arrivage::findOrFail($request->id); 
            $message = "L'arrivage a été mise à jour avec succès";

        } else {
            $arrivage = new Arrivage();  
        }
        $arrivage->total_poulet = $request->total;
        $arrivage->total_restant = $request->total;
        $arrivage->date_arrivage =  $request->date_arrivage;
        //Verification de l'existance du numero de bande
        $verif = Bande::where([['ferme_id',$request->ferme],['numero_bande',$request->bande]])->first();
        if($verif !=null){
            $bande_id = $verif->id;
        }else{
            $band = new Bande();
            $band->ferme_id = $request->ferme;
            $band->numero_bande = $request->bande;
            $band->save();
            $bande_id = $band->id;
        }
        $arrivage->bande_id = $bande_id;
        $arrivage->save();
        if($arrivage->id !=null){
            $id = $arrivage->id;
            if($request->quantite !=null) {  
                ArrivageProduit::where('arrivage_id',$id)->delete();
                $quantite = $request->quantite;
                $unite = $request->unite;
                $categorie = $request->categorie;
                $price = $request->price;
                $i=0;
                foreach($request->quantite as $key => $data){
                     
                    $qte = $quantite[$key];  
                    if($qte==null){
                        continue;
                    }
                    $unit = Unite::where('id',$unite[$key])->first();
                    $categ = Categorie::where('id',$categorie[$key])->first();
                    $produit = new ArrivageProduit();
                    $produit->arrivage_id = $id;
                    $produit->quantity = $qte;
                    $produit->quantity_restante = $qte;
                    $produit->categorie_id = $categorie[$key];
                    $produit->price = $price[$key];
                    $produit->name = $categ->name;
                    //$produit->name = $unit->name.'-'.$categ->name;
                    $produit->save();
                    $i++;  
                } 
                
            }
        }
        $notify[] = ['success', isset($message) ? $message : "$i nouveau(x) produits ont été ajoutés."];
         
        return back()->withNotify($notify);
    }

    public function storeDecoupe(Request $request)
    {
        $validationRule = [
            'arrivage' => 'required',  
            'quantitePrev' => 'required|array',
            'categorie' => 'required|array',
            'price' => 'required|array',  
            'quantite' => 'required|array'   
        ];
        $request->validate($validationRule);
  
         
        if($request->arrivage !=null){
            $id = $request->arrivage;
            if($request->quantite !=null) { 
                $quantitePrev = $request->quantitePrev;  
                $quantiteInit = $request->quantiteInit;  
                $quantite = $request->quantite;
                $unite = $request->unite;
                $categorie = $request->categorie;
                $price = $request->price;
                $i=0;
                
                foreach($quantitePrev as $key => $data){

                    if($data==null){
                        continue;
                    }
                     
                    if(array_values($quantite[$key])[0]==null){
                        continue;
                    }
                    $produit_Id = $key; 
                    $qte_prelevee = $data;
                    $qte_initiale = $quantiteInit[$key]; 
                    $product = ArrivageProduit::where('id',$produit_Id)->first();
                    
                    $product->quantity_prelevee = $product->quantity_prelevee + $qte_prelevee;
                    $product->quantity_restante = $product->quantity_restante - $qte_prelevee;
                    $product->save();

                    foreach($quantite[$key] as $key2 => $data2)
                    {
                      
                    if($data2 ==null){
                        continue;
                    }
                    $categorie_id = $key2;
                    $qte = $data2; 

                    $categ = Categorie::where('id',$categorie_id)->first();
                    $produit = ArrivageProduit::where([['parent_preleve',$produit_Id],['categorie_id',$categorie_id]])->first();
                    if($produit==null){
                        $produit = new ArrivageProduit();
                        $produit->quantity = $qte;
                        $produit->quantity_restante = $qte;
                    }else{
                        $produit->quantity = $produit->quantity + $qte;
                        $produit->quantity_restante = $produit->quantity_restante + $qte;
                    }
                    
                    $produit->arrivage_id = $id;
                    $produit->parent_preleve = $produit_Id;
                    $produit->niveau = 1; 
                    $produit->categorie_id = $categorie_id;
                    $produit->price = $categ->price;
                    $produit->name = $categ->name;
                    //$produit->name = $categ->unite->name.'-'.$categ->name;
                    $produit->save();
                    $i++;  
                    }
                } 
                
            }
        }
        $notify[] = ['success', isset($message) ? $message : "$i nouveau(x) produits ont été ajoutés."];
         
        return back()->withNotify($notify);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $arrivage = Arrivage::find($id);
        $fermes = Ferme::get();
        $pageTitle ='Détail de l\'arrivage abattoir';
        return view('admin.arrivage.show', compact('pageTitle','arrivage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $arrivage = Arrivage::find($id);
        $fermes = Ferme::get();
        $pageTitle ='Modification de l\'arrivage';
        return view('admin.arrivage.edit', compact('pageTitle','arrivage'));
    }

    public function send($id)
    {
       
        $produitNiv = ArrivageProduit::find($id);
        if($produitNiv !=null){
            $produit = new Produit();
            $produit->arrivage_id = $produitNiv->arrivage_id;
            $produit->categorie_id = $produitNiv->categorie_id;
            $produit->name = $produitNiv->name;
            $produit->price = $produitNiv->price;
            
            $produit->quantity = request()->qte;
            $produit->quantity_restante = request()->qte;
            $produit->save();
            
            $produitNiv->send = 1;
            $produitNiv->quantity_use = $produitNiv->quantity_use + request()->qte;
            $produitNiv->quantity_restante = $produitNiv->quantity_restante - request()->qte;
            $produitNiv->save();

            // if($produitNiv->decoupeDetail->count())
            // {  
            //     foreach($produitNiv->decoupeDetail as $data2){
            //         $produit2 = new Produit();
            //         $produit2->arrivage_id = $data2->arrivage_id;
            //         $produit2->categorie_id = $data2->categorie_id;
            //         $produit2->name = $data2->name;
            //         $produit2->price = $data2->price;
            //         $produit2->quantity = $data2->quantity_restante;
            //         $produit2->quantity_restante = $data2->quantity_restante;
            //         $produit2->save();

            //         $data2->send = 1;
            //         $data2->save(); 
            //     }

            // }
        }
        
        $notify[] = ['success',"Le produit a été envoyé"];
         
        return back()->withNotify($notify);
    }
    public function delete($id)
    { 
        Arrivage::where('id', decrypt($id))->delete();
        $notify[] = ['success', 'L\'arrivage a été supprimé avec succès'];
        return back()->withNotify($notify);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
