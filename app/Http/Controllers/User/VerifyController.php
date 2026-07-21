<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewNotification;
use App\Mail\KycUpload;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class VerifyController extends Controller
{
    public function verifyaccount(Request $request)
    {
        $request->validate([
          'id_number' => [
              'required',
              'string',
              'max:255',
              Rule::unique('users', 'id_number')->ignore(Auth::id(), 'id'),
          ],
          // Validate by the file's actual (server-guessed) type, not the
          // client-supplied extension, and cap the size at 5 MB.
          'idcard'   => ['nullable', 'file', 'mimes:pdf,doc,jpeg,jpg,png', 'max:5120'],
          'passport' => ['nullable', 'file', 'mimes:pdf,doc,jpeg,jpg,png', 'max:5120'],
], [
          'id_number.unique' => 'This ID number has already been used by another account please contact support if you have lost access to your old account, we only allow one account per user.',
          'idcard.mimes'   => 'The ID card must be a PDF, DOC, JPG, JPEG or PNG file.',
          'passport.mimes' => 'The passport must be a PDF, DOC, JPG, JPEG or PNG file.',
]);


        $settings = Settings::find(1);
        $strtxt   = $this->RandomStringGenerator(6);
        $whitelist = ['pdf', 'doc', 'jpeg', 'jpg', 'png'];

        $cardname = null;
        $passname = null;

        // Process ID Card
        if ($request->hasFile('idcard')) {
            $document1 = $request->file('idcard');
            $ext = strtolower($document1->getClientOriginalExtension());

            if (!in_array($ext, $whitelist)) {
                return redirect()->back()
                    ->with('message', 'Unaccepted ID Card Image Uploaded, try renaming the image file');
            }

            $nameWithoutExt = pathinfo($document1->getClientOriginalName(), PATHINFO_FILENAME);
            $nameWithoutExt = preg_replace('/[^A-Za-z0-9_-]/', '_', $nameWithoutExt);
            $cardname = "{$strtxt}_{$nameWithoutExt}_" . time() . ".{$ext}";

            if ($settings->location === "Local") {
                $document1->storeAs('public/photos', $cardname);
            } elseif ($settings->location !== "Email") {
                $filePath = "storage/{$cardname}";
                Storage::disk('s3')->put($filePath, file_get_contents($document1));
            }
        }

        // Process Passport
        if ($request->hasFile('passport')) {
            $document2 = $request->file('passport');
            $ext = strtolower($document2->getClientOriginalExtension());

            if (!in_array($ext, $whitelist)) {
                return redirect()->back()
                    ->with('message', 'Unaccepted Passport Image Uploaded, try renaming the image file');
            }

            $nameWithoutExt = pathinfo($document2->getClientOriginalName(), PATHINFO_FILENAME);
            $nameWithoutExt = preg_replace('/[^A-Za-z0-9_-]/', '_', $nameWithoutExt);
            $passname = "{$strtxt}_{$nameWithoutExt}_" . time() . ".{$ext}";

            if ($settings->location === "Local") {
                $document2->storeAs('public/photos', $passname);
            } elseif ($settings->location !== "Email") {
                $filePath = "storage/{$passname}";
                Storage::disk('s3')->put($filePath, file_get_contents($document2));
            }
        }

        // Handle Email location uploads
        if ($settings->location === "Email") {
            $data = [
                'document'  => $request->file('idcard') ?? null,
                'document1' => $request->file('passport') ?? null,
            ];
            // Mail::to($settings->contact_email)->send(new KycUpload($data));
        } else {
            // Send notification email
            $objDemo = new \stdClass();
            $objDemo->message = "This is to inform you of a successful KYC Document Upload that just occurred on your system. Please login to review documents.";
            $objDemo->sender = $settings->site_name;
            $objDemo->date = Carbon::now();
            $objDemo->subject = "KYC Verification";
            // Mail::bcc($settings->contact_email)->send(new NewNotification($objDemo));
        }

        // Update user record
        User::where('id', Auth::id())->update([
            'id_card'        => $cardname,
            'passport'       => $passname,
            'account_verify' => 'Under review',
        ]);

        return redirect()->back()
            ->with('success', 'Action Successful! Please wait for system to validate your submission.');
    }

    // Random string generator
    private function RandomStringGenerator($n)
    {
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        $generated_string = "";

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string .= $domain[$index];
        }

        return $generated_string;
    }
}
