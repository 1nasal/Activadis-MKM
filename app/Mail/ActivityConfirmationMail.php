<?php

namespace App\Mail;

use App\Models\External;
use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activity;
    public $external;
    public $token;

    public function __construct(Activity $activity, External $external, string $token)
    {
        $this->activity = $activity;
        $this->external = $external;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Bevestig je deelname: ' . $this->activity->name)
            ->view('emails.activity_confirmation');
    }
}
