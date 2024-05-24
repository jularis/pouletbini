<?php

namespace App\Http\Controllers\Staff;

use Carbon\Carbon;
use App\Models\Magasin;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Rules\FileTypeValidate;
use App\Models\LivraisonPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{

    public function dashboard()
    {
        $user            = auth()->user();
        $pageTitle       = "Staff Dashboard"; 
       // $deliveryInQueue = LivraisonInfo::deliveryQueue()->where([['receiver_staff_id', $user->id]])->count(); 

       $livraisonInfoCount = LivraisonInfo::where('status', 3)->where([['receiver_staff_id', $user->id]])->when(request()->date==null, function ($query) {
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

             ////////////////////////////////////////////////////////              
       $livraisonInfoCountCancel = LivraisonInfo::where('status', 1)->where([['receiver_staff_id', $user->id]])->when(request()->date==null, function ($query) {
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

       /////////////////////////////////////////
       $totalIncome      = LivraisonPayment::joinRelationship('info')->where('livraison_payments.status', Status::PAID)->where([['receiver_staff_id', $user->id]])->when(request()->date==null, function ($query) {
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
       $totalIncomeDays      = LivraisonPayment::joinRelationship('info')->where('livraison_payments.status', Status::PAID)->where([['receiver_staff_id', $user->id]])
       ->when(request()->date==null, function ($query) {
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

 ///////////////////////////////////////////////////////////////
       $deliveryInQueue  = LivraisonInfo::where('status', 2)
                        ->where([['receiver_staff_id', $user->id]])
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
        return view('staff.dashboard', compact('pageTitle','deliveryInQueue', 'totalIncome', 'livraisonInfoCount','livraisonInfoCountCancel','totalIncomeDays','totalLivraisonDays'));
    }

    public function magasinList()
    {
        $pageTitle = "Magasin List";
        $magasins  = Magasin::searchable(['name', 'email', 'address'])->active()->orderBy('name', 'ASC')->paginate(getPaginate());
        return view('staff.magasin.index', compact('pageTitle', 'magasins'));
    }


    public function profile()
    {
        $pageTitle = "Staff Profile";
        $staff     = auth()->user();
        return view('staff.profile', compact('pageTitle', 'staff'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'image'     => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image ?: null;
                $user->image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->email     = $request->email;
        $user->save();
        $notify[] = ['success', 'Your profile a été mise à jour avec succès.'];
        return redirect()->route('staff.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $user      = auth()->user();
        return view('staff.password', compact('pageTitle', 'user'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password do not match !!'];
            return back()->withNotify($notify);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return redirect()->route('staff.password')->withNotify($notify);
    }
}
