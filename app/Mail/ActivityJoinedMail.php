<?php

namespace App\Mail;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivityJoinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activity;
    public $name;
    public $leaveToken;

    public function __construct(Activity $activity, $name, $leaveToken = null)
    {
        $this->activity = $activity;
        $this->name = $name;
        $this->leaveToken = $leaveToken;
    }

    public function build()
    {
        return $this->subject('Bevestiging deelname: ' . $this->activity->name)
            ->view('emails.activity_joined');
    }
}
