<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Settings;
use App\Models\Agent;
use App\Models\CryptoAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $settings=Settings::where('id','1')->first();
        $request = request();

        // If the user arrived via a referral link, the code is held in the
        // session. Use it so the required-referral validation below passes.
        if (session('ref_by')) {
            $input['ref_by'] = session('ref_by');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username'=> ['required', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'ref_by' => ['nullable', 'exists:users,username'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ];

        if ($settings->captcha == "true") {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        Validator::make($input, $rules, [
            'ref_by.exists' => 'The referral code you entered is invalid.',
        ])->validate();
        
        
        if(session('ref_by')) {
            $ref_by = session('ref_by');
            $user= User::where('username', $ref_by)->first();
            $ref_by_id = $user->id;
        }else {
            if (!empty($input['ref_by'])) {
                $sponsor = User::where('username', $input['ref_by'])->first();
                $ref_by_id = $sponsor->id;
            }else {
                $ref_by_id = NULL;
            }
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'username'=> $input['username'],
            'country' => $input['country'],
            'ref_by' => $ref_by_id,
            'status' =>'active',
            'password' => Hash::make($input['password']),
        ]);

        $cryptoaccnt = new CryptoAccount();
        $cryptoaccnt->user_id = $user->id;
        $cryptoaccnt->save();
        
        $request->session()->forget('ref_by');
        return $user;
    }
}
