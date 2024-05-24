<?php

namespace App\Http\Controllers\Manager;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Magasin;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\LivraisonInfo;
use App\Models\SupportMessage;
use App\Rules\FileTypeValidate;
use App\Models\LivraisonPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{

    public function dashboard()
    {
        $manager            = auth()->user();
        $pageTitle          = "Manager Dashboard"; 
        //$totalSentCount     = LivraisonInfo::where('sender_magasin_id', $manager->magasin_id)->where('status', '!=', Status::COURIER_QUEUE)->count();

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
        return view('manager.dashboard', compact('pageTitle', 'deliveryInQueue', 'magasinCount', 'totalIncome', 'magasins', 'managerCount', 'livraisonInfoCount','livraisonInfoCountCancel','totalIncomeDays','totalLivraisonDays'));
    }

    protected function livraisons($scope = null)
    {
        $user     = auth()->user();
        $livraisons = LivraisonInfo::where(function ($query) use ($user) {
            $query->where('sender_magasin_id', $user->magasin_id)->orWhere('receiver_magasin_id', $user->magasin_id);
        });
        if ($scope) {
            $livraisons = $livraisons->$scope();
        }
        $livraisons = $livraisons->dateFilter()->searchable(['code'])->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->paginate(getPaginate());
        return $livraisons;
    }

    public function magasinList()
    {
        $pageTitle = "Liste des Magasins";
        $magasins  = Magasin::active()->searchable(['name', 'email', 'address'])->orderBy('name')->paginate(getPaginate());
        return view('manager.magasin.index', compact('pageTitle', 'magasins'));
    }

    public function profile()
    {
        $pageTitle = "Manager Profile";
        $manager   = auth()->user();
        return view('manager.profile', compact('pageTitle', 'manager'));
    }

    public function ticketDelete($id)
    {
        $message = SupportMessage::findOrFail($id);
        $path    = getFilePath('ticket');

        if ($message->attachments()->count() > 0) {

            foreach ($message->attachments as $attachment) {
                fileManager()->removeFile($path . '/' . $attachment->attachment);
                $attachment->delete();
            }
        }

        $message->delete();
        $notify[] = ['success', "Support ticket deleted successfully"];
        return back()->withNotify($notify);
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'image'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
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
        $notify[] = ['success', 'Your profile a été ajouté avec succès'];
        return redirect()->route('manager.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $user      = auth()->user();
        return view('manager.password', compact('pageTitle', 'user'));
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
        $notify[] = ['success', 'Password changed successfully'];
        return redirect()->route('manager.password')->withNotify($notify);
    }

    public function magasinIncome()
    {
        $user          = auth()->user();
        $pageTitle     = "Revenus des Magasins";
        $magasinIncomes = LivraisonPayment::where('magasin_id', $user->magasin_id)
            ->select(DB::raw("*,SUM(final_amount) as totalAmount"))
            ->groupBy('date')->orderby('id', 'DESC')->paginate(getPaginate());
        return view('manager.livraison.income', compact('pageTitle', 'magasinIncomes'));
    }
}
