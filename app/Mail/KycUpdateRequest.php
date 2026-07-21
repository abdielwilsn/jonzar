<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycUpdateRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('KYC Update Request')
                    ->view('emails.kyc_update_request')
                    ->with([
                        'dashboard_url' => config('app.url') . '/dashboard', 
                        'app_name' => config('app.name'),
                        'user_name' => $this->user->name,
                    ]);
    }
}
