<?php

namespace App\Http\Controllers\admin;

use App\Models\Bande;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ferme;

class BandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageTitle      = "Gestion des bandes";
        $fermes = Ferme::get();
        $bandes = Bande::dateFilter()->searchable(['numero_bande'])
                                ->latest('id')
                                ->with('ferme')
                                ->paginate(getPaginate());
        return view('admin.bande.index', compact('pageTitle','bandes','fermes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $pageTitle = "Ajouter une Bande";
        $fermes = Ferme::get();
        return view('admin.bande.create', compact('pageTitle','fermes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required',
            'ferme' => 'required' 
        ]);
        if ($request->id) {
            $bande  = Bande::findOrFail($request->id);
            $message = "La Bande a été mise à jour avec succès";
        } else {
            $bande  = new Bande();
            $message = "La Bande a été ajouté avec succès";
        }

        $bande->numero_bande = $request->numero;
        $bande->ferme_id = $request->ferme; 
        $bande->save();
        $notify[] = ['success',$message];
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
         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bande = Bande::find($id);
        $fermes = Ferme::get();
        $pageTitle ='Modification de la Bande';
        return view('admin.bande.edit', compact('pageTitle','bande','fermes'));
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
