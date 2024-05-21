<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Magasin;
use App\Lib\CurlRequest;
use App\Models\Arrivage;
use App\Constants\Status;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Rules\FileTypeValidate;
use App\Models\LivraisonPayment;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LivraisonProduct;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {

        $pageTitle        = 'Dashboard';
        $magasinCount      = Magasin::count();
        $magasins         = Magasin::orderBy('name', 'ASC')->take(5)->get();

        $livraisonInfoCount = LivraisonInfo::where('status', 3)->when(request()->date==null, function ($query) {
                            $query->whereBetween('estimate_date',[date('Y-m-01'),date('Y-m-t')]);
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
                                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
                            })->count();
        $livraisonInfoCountCancel = LivraisonInfo::where('status', 1)->when(request()->date==null, function ($query) {
                            $query->whereBetween('estimate_date',[date('Y-m-01'),date('Y-m-t')]);
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
                                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
                            })->count();
        $managerCount     = User::manager()->count();

        /////////////////////////////////////////
        $totalIncome      = LivraisonPayment::joinRelationship('info')->where('livraison_payments.status', Status::PAID)->when(request()->date==null, function ($query) {
            $query->whereBetween('estimate_date',[date('Y-m-01'),date('Y-m-t')]);
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
                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
            })->sum('final_amount');


        ///////////////////////////////////////////////////////
        $totalIncomeDays      = LivraisonPayment::joinRelationship('info')->where('livraison_payments.status', Status::PAID)->when(request()->date==null, function ($query) {
            $query->whereDate('estimate_date',gmdate('Y-m-d'));
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
                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
            })->sum('final_amount');

        $totalLivraisonDays      = LivraisonInfo::where('status', 3)->when(request()->date==null, function ($query) {
            $query->whereDate('estimate_date',gmdate('Y-m-d'));
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
                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
            })->count();

  
        $deliveryInQueue  = LivraisonInfo::where('status', 2)
                        ->when(request()->date==null, function ($query) {
                            $query->whereBetween('estimate_date',[date('Y-m-01'),date('Y-m-t')]);
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
                                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
                            })->count(); 

        $arrivageByFerme = Arrivage::joinRelationship('bande.ferme')->whereBetween('date_arrivage',[date('Y-m-01'),date('Y-m-t')])->select('fermes.nom', DB::RAW('count(arrivages.id) as total'))->groupby('ferme_id')->get();

        $livraisonByCategorie = LivraisonProduct::joinRelationship('info')->joinRelationship('produit')->joinRelationship('produit.categorie')->where('livraison_infos.status',3)->when(request()->date==null, function ($query) {
                            $query->whereDate('estimate_date',gmdate('Y-m-d'));
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
                                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
                            })->select('categories.name', DB::RAW('count(livraison_products.id) as total'))->groupby('categorie_id')->get();
          
        $livraisonByLivreur = LivraisonInfo::joinRelationship('senderStaff')->where('livraison_infos.status',3)->when(request()->date==null, function ($query) {
                            $query->whereDate('estimate_date',gmdate('Y-m-d'));
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
                                $query->whereDate('estimate_date', '>=', $startDate)->whereDate('estimate_date', '<=', $endDate);
                            })->select(DB::RAW('concat(lastname," ", firstname) as name'), DB::RAW('count(livraison_infos.id) as total'))->groupby('sender_staff_id')->get();
    
        
        return view('admin.dashboard', compact('pageTitle', 'livraisonByLivreur', 'deliveryInQueue', 'magasinCount', 'totalIncome', 'magasins', 'managerCount', 'livraisonInfoCount','livraisonInfoCountCancel','totalIncomeDays','arrivageByFerme','livraisonByCategorie','totalLivraisonDays'));
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $admin     = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible de charger l\'image.'];
                return back()->withNotify($notify);
            }
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile a été mise à jour avec succès'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin     = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();

        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Le Mot de passe ne correspond pas!!'];
            return back()->withNotify($notify);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Mot de passe a été changé avec succès.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $pageTitle     = 'Notifications';
        return view('admin.notifications', compact('pageTitle', 'notifications'));
    }

    public function notificationRead($id)
    {
        $notification              = AdminNotification::findOrFail($id);
        $notification->read_status = Status::YES;
        $notification->save();
        $url = $notification->click_url;

        if ($url == '#') {
            $url = url()->previous();
        }

        return redirect($url);
    }

    public function requestReport()
    {
        $pageTitle            = 'Your Listed Report & Request';
        $arr['app_name']      = systemDetails()['name'];
        $arr['app_url']       = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $url                  = "https://pouletbini.com/issue/get?" . http_build_query($arr);
        $response             = CurlRequest::curlContent($url);
        $response             = json_decode($response);

        if ($response->status == 'error') {
            return to_route('admin.dashboard')->withErrors($response->message);
        }

        $reports = $response->message[0];
        return view('admin.reports', compact('reports', 'pageTitle'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type'    => 'required|in:bug,feature',
            'message' => 'required',
        ]);
        $url = 'https://pouletbini.com/issue/add';

        $arr['app_name']      = systemDetails()['name'];
        $arr['app_url']       = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASE_CODE');
        $arr['req_type']      = $request->type;
        $arr['message']       = $request->message;
        $response             = CurlRequest::curlPostContent($url, $arr);
        $response             = json_decode($response);

        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }

        $notify[] = ['success', $response->message];
        return back()->withNotify($notify);
    }

    public function readAll()
    {
        AdminNotification::where('read_status', Status::NO)->update([
            'read_status' => Status::YES,
        ]);
        $notify[] = ['success', 'Notifications lues avec succès.'];
        return back()->withNotify($notify);
    }

    public function allAdmin()
    {
        $pageTitle = 'Tous les Admins';
        $admins    = Admin::orderBy('id', 'desc')->paginate(getPaginate());
        $adminId   = auth()->guard('admin')->user()->id;
        
        
        return view('admin.all', compact('admins', 'pageTitle', 'adminId'));
    }

    public function adminStore(Request $request)
    {
        $id = $request->id ?? 0;

        $request->validate([
            'email'    => 'required|email|unique:admins,email,' . $id,
            'username' => 'required|unique:admins,username,' . $id,
            'password' => !$id ? 'required|confirmed' : 'nullable',
            "name"     => 'required'
        ]);

        if ($id) {
            if ($id == Status::SUPER_ADMIN_ID) {
                $notify[] = ['error', 'Désolé! Vous ne pouvez pas faire la mise à jour d\'Administrateur princial'];
                return back()->withNotify($notify);
            }
            $adminId = auth()->guard('admin')->user()->id;
            if ($adminId != Status::SUPER_ADMIN_ID) {
                $notify[] = ['error', 'Seul l\'Administrateur principal peut faire la mise à jour des autres Administrateurs.'];
                return back()->withNotify($notify);
            }
            $admin = Admin::findOrFail($id);
        } else {
            $admin = new Admin();
            $admin->password = Hash::make($request->password);
        }
        $admin->name     = $request->name;
        $admin->email    = $request->email;
        $admin->username = $request->username;
        $admin->password  = $request->password ? Hash::make($request->password) : $admin->password;
        $admin->save();
        $notify[] = ['success', 'Admin a été ajouté avec succès'];
        return back()->withNotify($notify);
    }

    public function adminRemove($id)
    {
        if ($id == Status::SUPER_ADMIN_ID) {
            $notify[] = ['error', 'Désolé! Vous ne pouvez pas faire la mise à jour d\'Administrateur princial.'];
            return back()->withNotify($notify);
        }

        $adminId = auth()->guard('admin')->user()->id;
        if ($adminId != Status::SUPER_ADMIN_ID) {
            $notify[] = ['error', 'Seul l\'Administrateur principal peut faire la mise à jour des autres Administrateurs.'];
            return back()->withNotify($notify);
        }

        $admin = Admin::findOrFail($id);
        $admin->delete();

        $notify[] = ['success', 'Admin a été supprimé avec succès'];
        return back()->withNotify($notify);
    }

    public function adminStatus($id)
    {
        return Admin::changeStatus($id);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }
}
