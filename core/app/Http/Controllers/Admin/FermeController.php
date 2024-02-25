<?php

namespace App\Http\Controllers\admin;

use App\Models\Ferme;
use App\Http\Helpers\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FermeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageTitle      = "Gestion des fermes";
        $fermes = Ferme::dateFilter()->searchable(['nom'])
                                ->latest('id')
                                ->paginate(getPaginate());
        return view('admin.ferme.index', compact('pageTitle','fermes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFermeModal()
    {
        //
        $pageTitle = "Ajouter une Ferme";
        
        return view('admin.ferme.create-modal', compact('pageTitle'));
    }
    public function create()
    {
        //
        $pageTitle = "Ajouter une Ferme";
        return view('admin.ferme.create', compact('pageTitle'));
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
            'nom' => 'required',
            'lieu' => 'required' 
        ]);
        if ($request->id) {
            $ferme  = Ferme::findOrFail($request->id);
            $message = "La Ferme a été mise à jour avec succès";
        } else {
            $ferme  = new Ferme();
            $message = "La Ferme a été ajouté avec succès";
        }

        $ferme->nom = $request->nom;
        $ferme->lieu = $request->lieu;
        $ferme->responsable = $request->responsable;
        $ferme->contact = $request->contact;
        $ferme->save();
        $notify[] = ['success',$message];

        // if (request()->ajax) {   
        //     $contents = array();
        //     $fermes = Ferme::get();
        //     if ($fermes->count()) {
        //         $results = '<option value=""></option>';

        //         foreach ($fermes as $data) { 
        //             $results .= '<option value="' . $data->id . '>' . $data->nom . '</option>';
        //         }
        //     }
        // $contents = 'success';
        // $contents['data'] = $results;
        // $contents['status'] = 'success'; 
        // $content = "success";
        // return $content; 
        // }
        return back()->withNotify($notify);
    }

    public function storeModal(Request $request)
    { 
         
        $ferme  = new Ferme(); 

        $ferme->nom = $request->nom;
        $ferme->lieu = $request->lieu;
        $ferme->responsable = $request->responsable;
        $ferme->contact = $request->contact;
        $ferme->save();
        $message = "La Ferme a été ajouté avec succès";
        $notify[] = ['success',$message];
   
            $contents = array();
            $fermes = Ferme::get();
            if ($fermes->count()) {
                $results = '<option value=""></option>';

                foreach ($fermes as $data) { 
                    $results .= '<option value="' . $data->id . '>' . $data->nom . '</option>';
                }
            } 
        $contents['data'] = $results;
        $contents['status'] = 'success'; 
        
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
        $ferme = Ferme::find($id);
        $pageTitle ='Modification de la Ferme';
        return view('admin.ferme.edit', compact('pageTitle','ferme'));
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
