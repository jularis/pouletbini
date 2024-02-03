<?php

namespace App\Http\Controllers\admin;

use App\Models\Bande;
use App\Models\Ferme;
use App\Models\Arrivage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Unite;

class ArrivageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle      = "Gestion des arrivages";
        $fermes = Ferme::get();
        $bandes = Bande::dateFilter()->searchable(['numero_bande'])
                                ->latest('id')
                                ->with('ferme')
                                ->get();
        $arrivages = Arrivage::dateFilter()->searchable([])
                                ->latest('id')
                                ->with('bande')
                                ->paginate(getPaginate());
        return view('admin.arrivage.index', compact('pageTitle','bandes','fermes','arrivages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Ajouter une Bande";
        $fermes = Ferme::get(); 
        $categories = Categorie::with('unite')->get();
        return view('admin.arrivage.create', compact('pageTitle','fermes','categories'));
    }

    public function getBande()
    { 
        $id = request()->ferme;
        $bandes = Bande::where('ferme_id', $id)->get();
        if ($bandes->count()) {
            $contents = '<option value=""></option>';

            foreach ($bandes as $data) {
                $contents .= '<option value="' . $data->numero_bande . '" >' . $data->numero_bande . '</option>';
            }
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
                Produit::where('arrivage_id',$id)->delete();
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
                    $produit = new Produit();
                    $produit->arrivage_id = $id;
                    $produit->quantity = $qte;
                    $produit->quantity_restante = $qte;
                    $produit->categorie_id = $categorie[$key];
                    $produit->price = $price[$key];
                    $produit->name = $unit->name.'-'.$categ->name;
                    $produit->save();
                    $i++;  
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
        //
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
