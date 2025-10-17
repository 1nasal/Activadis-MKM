<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $activationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->activationUrl = route('activation.show', $user->activation_token);
    }

    public function build()
    {
        return $this->subject('Activeer je account')
                    ->view('emails.user-activation');
    }
}