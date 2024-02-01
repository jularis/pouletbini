<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function create()
    {
        $pageTitle = "Ajouter un Staff";
        return view('manager.staff.create', compact('pageTitle'));
    }

    public function index()
    {
        $pageTitle = "Tous les Staffs";
        $manager   = auth()->user();
        $staffs    = User::searchable(['username', 'email', 'mobile'])->where(function ($query) use ($manager) {
            $query->staff()->where('magasin_id', $manager->magasin_id);
        })->with('magasin')->orderBy('id', 'DESC')->paginate(getPaginate());

        return view('manager.staff.index', compact('pageTitle', 'staffs'));
    }

    public function staffLogin($id)
    {
        User::staff()->where('id', $id)->firstOrFail();
        auth()->loginUsingId($id);
        return to_route('staff.dashboard');
    }
    
    public function edit($id)
    {
        try {
            $id = decrypt($id);
        } catch (Exception $ex) {
            $notify[] = ['error', "Invalid URL."];
            return back()->withNotify($id);
        }

        $pageTitle = "Mise à jour de Staff";
        $manager   = auth()->user();
        $staff     = User::where('id', $id)->where('magasin_id', $manager->magasin_id)->firstOrFail();
        return view('manager.staff.edit', compact('pageTitle', 'staff'));
    }

    public function store(Request $request)
    {
        $manager        = auth()->user();
        $validationRule = [
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
        ];

        if ($request->id) {
            $validationRule = array_merge($validationRule, [
                'email'    => 'required|email|max:255|unique:users,email,' . $request->id,
                'username' => 'required|max:255|unique:users,username,' . $request->id,
                'mobile'   => 'required|max:255|unique:users,mobile,' . $request->id,
                'password' => 'nullable|confirmed|min:4',
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

        $staff = new User();

        if ($request->id) {
            $staff   = User::where('id', $request->id)->where('magasin_id', $manager->magasin_id)->firstOrFail();
            $message = "Le Staff a été mise à jour avec succès";
        }

        $staff->magasin_id = $manager->magasin_id;
        $staff->firstname = $request->firstname;
        $staff->lastname  = $request->lastname;
        $staff->username  = $request->username;
        $staff->email     = $request->email;
        $staff->mobile    = $request->mobile;
        $staff->address    = $request->address;
        $staff->user_type = "staff";
        $staff->password  = $request->password ? Hash::make($request->password) : $staff->password;
        $staff->save();

        if (!$request->id) {
            notify($staff, 'STAFF_CREATE', [
                'username' => $staff->username,
                'email'    => $staff->email,
                'password' => $request->password,
            ]);
        }

        $notify[] = ['success', isset($message) ? $message : 'Le Staff a été ajouté avec succès'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return User::changeStatus($id);
    }
}
