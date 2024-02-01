<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magasin;
use Illuminate\Http\Request;

class MagasinController extends Controller
{

    public function index()
    {
        $pageTitle = "Gestion Magasin";
        $magasins  = Magasin::searchable(['name', 'email', 'phone', 'address'])->orderBy('name', 'ASC')->paginate(getPaginate());
        return view('admin.magasin.index', compact('pageTitle', 'magasins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|max:255',
            'address' => 'required|max:255',
        ]);
        
        if ($request->id) {
            $magasin  = Magasin::findOrFail($request->id);
            $message = "Magasin a été mise à jour avec succès";
        } else {
            $magasin  = new Magasin();
            $message = "Magasin a été ajouté avec succès";
        }

        $magasin->name    = $request->name;
        $magasin->email   = $request->email;
        $magasin->phone   = $request->phone;
        $magasin->address = $request->address;
        $magasin->save();

        $notify[] = ['success',$message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Magasin::changeStatus($id);
    }
}
