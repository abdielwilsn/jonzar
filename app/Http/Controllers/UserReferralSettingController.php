<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReferralSetting;
use Illuminate\Support\Facades\Auth;

class UserReferralSettingController extends Controller
{
    // Return view for user referral settings
    public function referralView(Request $request)
    {
        $userReferralSetting = UserReferralSetting::where('user_id', Auth::id())->first();

        // If no settings exist for the user, create a default entry
        if (!$userReferralSetting) {
            $userReferralSetting = UserReferralSetting::create([
                'user_id' => Auth::id(),
                'referral_commission' => 0.00,
                'referral_commission1' => 0.00,
                'referral_commission2' => 0.00,
                'referral_commission3' => 0.00,
                'referral_commission4' => 0.00,
                'referral_commission5' => 0.00,
                'signup_bonus' => 0.00,
            ]);
        }

        return view('user.referral_settings.show', [
            'title' => 'User Referral Settings',
            'settings' => $userReferralSetting,
        ]);
    }

    // Update user referral settings
    public function updateRefBonus(Request $request)
    {
        // Validate the request
//        $request->validate([
//            'id' => 'nullable|exists:user_referral_settings,id',
//            'user_id' => 'required|exists:users,id',
//            'ref_commission' => 'required|numeric|min:0',
//            'ref_commission1' => 'required|numeric|min:0',
//            'ref_commission2' => 'required|numeric|min:0',
//            'ref_commission3' => 'required|numeric|min:0',
//            'ref_commission4' => 'required|numeric|min:0',
//            'ref_commission5' => 'required|numeric|min:0',
//            'signup_bonus' => 'required|numeric|min:0',
//        ]);
//
//        // Check if the authenticated admin has permission to edit this user's settings
//        if (!Auth::guard('admin')->check()) {
//            return response()->json(['status' => 403, 'error' => 'Unauthorized action'], 403);
//        }

        // Prepare the data for create/update
        $data = [
            'user_id' => $request->user_id,
            'referral_commission' => $request->ref_commission,
            'referral_commission1' => $request->ref_commission1,
            'referral_commission2' => $request->ref_commission2,
            'referral_commission3' => $request->ref_commission3,
            'referral_commission4' => $request->ref_commission4,
            'referral_commission5' => $request->ref_commission5,
            'signup_bonus' => $request->signup_bonus,
        ];

        // Check if a record exists for the user
        $referralSetting = UserReferralSetting::where('user_id', $request->user_id)->first();

        if ($referralSetting) {
            // Update existing record
            $referralSetting->update($data);
        } else {
            // Create new record
            UserReferralSetting::create($data);
        }

        return response()->json(['status' => 200, 'success' => 'User Referral Bonus Settings Saved Successfully']);
    }
}
