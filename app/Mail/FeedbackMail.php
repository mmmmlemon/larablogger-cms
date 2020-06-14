<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    //параметры которые мы получаем из формы обратной связи, которые попадут в письмо
    public $email;
    public $title;
    public $feedback;
    public $date;
    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($email, $title, $feedback)
    {   
        $this->email = $email;
        $this->title = $title;
        $this->feedback = $feedback;
        $this->date = Carbon::today()->format("d.m.Y");
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $contact_email = App\Settings::all()->first()->from_email;
        return $this->from($contact_email)->subject("Feedback message")->view('emails.feedback');
    }
}
