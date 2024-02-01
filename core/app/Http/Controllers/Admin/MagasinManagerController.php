<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Magasin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MagasinManagerController extends Controller
{

    public function index()
    {
        $pageTitle      = "Tous les Managers de Magasins";
        $magasinManagers = User::searchable(['username', 'email', 'mobile', 'magasin:name'])->manager()->latest('id')->with('magasin')->paginate(getPaginate());
        return view('admin.manager.index', compact('pageTitle', 'magasinManagers'));
    }

    public function create()
    {
        $pageTitle = "Ajouter Magasin Manager";
        $magasins  = Magasin::active()->orderBy('name')->get();
        return view('admin.manager.create', compact('pageTitle', 'magasins'));
    }

    public function store(Request $request)
    {
        $validationRule = [
            'magasin'    => 'required|exists:magasins,id',
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
        ];

        if ($request->id) {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:255|unique:users,email,' . $request->id,
                'username' => 'required|max:255|unique:users,username,' . $request->id,
                'mobile'   => 'required|max:255|unique:users,mobile,' . $request->id,
            ]);
        } else {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:255|unique:users',
                'username' => 'required|max:255|unique:users',
                'mobile'   => 'required|max:255|unique:users',
                'password' => 'required|confirmed|min:4',

            ]);
        }

        $request->validate($validationRule);

        $magasin = Magasin::where('id', $request->magasin)->first();

        if ($magasin->status == Status::NO) {
            $notify[] = ['error', 'This magasin is inactive'];
            return back()->withNotify($notify)->withInput();
        }

        if ($request->id) {
            $manager = User::findOrFail($request->id);
            $message = "Manager a été mise à jour avec succès";
        } else {
            $manager           = new User();
            $manager->password = Hash::make($request->password);
        }

        if ($manager->magasin_id != $request->magasin) {
            $hasManager = User::manager()->where('magasin_id', $request->magasin)->exists();
            if ($hasManager) {
                $notify[] = ['error', 'This magasin has already a manager'];
                return back()->withNotify($notify)->withInput();
            }
        }


        $manager->magasin_id = $request->magasin;
        $manager->firstname = $request->firstname;
        $manager->lastname  = $request->lastname;
        $manager->username  = $request->username;
        $manager->email     = $request->email;
        $manager->mobile    = $request->mobile;
        $manager->address    = $request->address;
        $manager->user_type = "manager";
        $manager->password  = $request->password ? Hash::make($request->password) : $manager->password;
        $manager->save();

        if (!$request->id) {
            notify($manager, 'MANAGER_CREATE', [
                'username' => $manager->username,
                'email'    => $manager->email,
                'password' => $request->password,
            ]);
        }

        $notify[] = ['success', isset($message) ? $message : 'Manager a été ajouté avec succès'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Mise à jour Magasin Manager";
        $magasins  = Magasin::active()->orderBy('name')->get();
        $manager   = User::findOrFail($id);
        return view('admin.manager.edit', compact('pageTitle', 'magasins', 'manager'));
    }

    public function staffList($magasinId)
    {
        $pageTitle = "Liste des Staffs";
        $staffs = User::searchable(['username', 'email', 'mobile', 'magasin:name'])->staff()->where('magasin_id', $magasinId)->with('magasin')->paginate(getPaginate());
        return view('admin.manager.staff', compact('pageTitle', 'staffs'));
    }

    public function status($id)
    {
        return User::changeStatus($id);
    }

    public function login($id)
    {
        User::manager()->where('id', $id)->firstOrFail();
        auth()->loginUsingId($id);
        return to_route('manager.dashboard');
    }

    public function staffLogin($id)
    {
        User::staff()->where('id', $id)->firstOrFail();
        auth()->loginUsingId($id);
        return to_route('staff.dashboard');
    }

    public function magasinManager($id)
    {
        $magasin         = Magasin::findOrFail($id);
        $pageTitle      = $magasin->name . " Manager List";
        $magasinManagers = User::manager()->where('magasin_id', $id)->orderBy('id', 'DESC')->with('magasin')->paginate(getPaginate());
        return view('admin.manager.index', compact('pageTitle', 'magasinManagers'));
    }
}
