<?php

namespace App\Http\Controllers\Manager;

use Excel;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Produit;
use App\Constants\Status;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Imports\ClientImport;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Models\LivraisonProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LivraisonSettingController extends Controller
{

    public function categorieIndex()
    {
        $pageTitle = "Gestion Categorie";
        $categories     = Categorie::with('unite')->orderBy('name')->paginate(getPaginate());
        return view('manager.categorie.categorie', compact('pageTitle', 'categories'));
    }

    public function categorieStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        if ($request->id) {
            $categorie    = Categorie::findOrFail($request->id);
            $message = 'Categorie a été mise à jour avec succès';
        } else {
            $categorie = new Categorie();
        }
        $categorie->name   = $request->name;
        $categorie->save();
        $notify[] = ['success', isset($message) ? $message : 'Categorie a été ajouté avec succès'];
        return back()->withNotify($notify);
    }

    public function produitIndex()
    {
        $pageTitle = "Gestion des Produits";
        $categories     = Categorie::active()->orderBy('name')->get();
        $produits     = Produit::orderBy('name')->where('quantity_restante','>',0)
                                ->with('categorie')
                                ->groupby('categorie_id')
                                ->select('id','produits.arrivage_id','produits.categorie_id','produits.name','produits.price','produits.status','produits.created_at','produits.updated_at'
                                ,DB::RAW('SUM(quantity) as quantity'),DB::RAW('SUM(quantity_use) as quantity_use'),DB::RAW('SUM(quantity_restante) as quantity_restante'))
                                ->paginate(getPaginate());
      
        return view('manager.categorie.produit', compact('pageTitle', 'produits', 'categories'));
    }

    public function produitStore(Request $request)
    {
        $request->validate([
            'categorie'  => 'required|exists:categories,id',
            'name'  => 'required',
            'price' => 'required|gt:0|numeric',
            'quantity' => 'required|gt:0|numeric'
        ]);

        if ($request->id) {
            $categorie    = Produit::findOrFail($request->id);
            $message = "Produit a été mise à jour avec succès";
        } else {
            $categorie = new Produit();
        }
        $categorie->name    = $request->name;
        $categorie->categorie_id = $request->categorie;
        $categorie->price   = $request->price;
        $categorie->quantity   = $request->quantity;
        $categorie->save();
        $notify[] = ['success', isset($message) ? $message  : 'Produit a été ajouté avec succès'];
        return back()->withNotify($notify);
    }

    public function clientIndex()
    {
        $pageTitle = "Gestion des Clients"; 
        $clients     = Client::searchable(['name', 'genre','email','phone','address'])->orderBy('id','desc')->paginate(getPaginate());
        return view('manager.categorie.client', compact('pageTitle', 'clients'));
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'phone'  => 'required',
        ]);

        if ($request->id) {
            $client    = Client::findOrFail($request->id);
            $message = "Client a été mise à jour avec succès";
        } else {
            $client = new Client();
        }
        $client->name    = $request->name;
        $client->genre = $request->genre;
        $client->phone   = $request->phone;
        $client->email   = $request->email;
        $client->address   = $request->address;
        $client->save();
        $notify[] = ['success', isset($message) ? $message  : 'Client a été ajouté avec succès'];
        return back()->withNotify($notify);
    }
    public function clientDelete($id)
    { 
        Client::where('id', decrypt($id))->delete();
        $notify[] = ['success', 'Le client supprimé avec succès'];
        return back()->withNotify($notify);
    }

    public function exportExcel()
    { 
        $filename = 'clients-' . gmdate('dmYhms') . '.xlsx';
        return Excel::download(new ExportClients, $filename);
    }
     

    public function  uploadContent(Request $request)
    {
        Excel::import(new ClientImport, $request->file('uploaded_file'));
        return back();
    }
    public function status($id)
    {
        return Categorie::changeStatus($id);
    }

    public function produitStatus($id)
    {
        return Produit::changeStatus($id);
    }
    public function clientStatus($id)
    {
        return Client::changeStatus($id);
    }
    public function magasinIncome()
    {
        $pageTitle     = "Historique des Revenus de Magasins";
        $magasins      = Magasin::active()->latest('id')->get();
        $magasinIncomes = LivraisonPayment::where('magasin_id','!=',0)->dateFilter()->filter(['magasin_id'])->where('status', Status::PAID)->select(DB::raw("*,SUM(final_amount) as totalAmount"))
            ->groupBy('magasin_id')->with('magasin')->paginate(getPaginate());
        return view('manager.livraison.income', compact('pageTitle', 'magasinIncomes', 'magasins'));
    }
}
