<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StaffController extends Controller
{
	public function list()
	{
		$pageTitle = 'Liste des Staffs';
		$staffs = User::searchable(['username', 'email', 'mobile', 'magasin:name'])->staff()->with('magasin')->paginate(getPaginate());
		return view('admin.staff.index', compact('pageTitle', 'staffs'));
	}
}
