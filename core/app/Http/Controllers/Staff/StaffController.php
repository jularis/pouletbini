<?php

namespace App\Http\Controllers\Staff;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Magasin;
use App\Models\LivraisonInfo;
use App\Models\LivraisonPayment;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{

    public function dashboard()
    {
        $user            = auth()->user();
        $pageTitle       = "Staff Dashboard";
        $magasinCount     = Magasin::active()->count();
        $cashCollection  = LivraisonPayment::joinRelationship('info')->where('receiver_staff_id', $user->id)->where([['livraison_payments.status', Status::PAID]])->sum('final_amount');
        $dispatchLivraison = LivraisonInfo::dispatched()->where([['receiver_staff_id', $user->id]])->count();
        $sentInQueue     = LivraisonInfo::queue()->where([['receiver_staff_id', $user->id]])->count();
        $deliveryInQueue = LivraisonInfo::deliveryQueue()->where([['receiver_staff_id', $user->id]])->count();
        $upcomingLivraison = LivraisonInfo::upcoming()->where([['receiver_staff_id', $user->id]])->count();
        $totalSent       = LivraisonInfo::where([['sender_staff_id', $user->id]])->whereIn('status', [Status::COURIER_DISPATCH, Status::COURIER_DELIVERYQUEUE, Status::COURIER_DELIVERED])->count();
        $totalDelivery   = LivraisonInfo::where([['receiver_staff_id', $user->id]])->where('status', Status::COURIER_DELIVERED)->count();

        $livraisonDelivery = LivraisonInfo::upcoming()->where([['receiver_staff_id', $user->id]])->orderBy('id', 'DESC')->with('senderMagasin', 'receiverMagasin', 'senderStaff', 'receiverStaff', 'paymentInfo')->take(5)->get();
        $totalLivraison    = LivraisonInfo::where([['receiver_staff_id', $user->id]])->count();
        return view('staff.dashboard', compact('pageTitle', 'magasinCount', 'deliveryInQueue', 'totalSent', 'upcomingLivraison', 'sentInQueue', 'dispatchLivraison', 'cashCollection', 'totalDelivery', 'livraisonDelivery', 'totalLivraison'));
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
