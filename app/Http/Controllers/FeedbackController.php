<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\FeedbackMail;

use Illuminate\Support\Facades\Mail;

use App;

//functions for Feedback
class FeedbackController extends Controller
{
    //send feedback email to SMTP server
    public function mail(Request $request)
    {
        //get email for feedback
        $email = App\Settings::all()->first()->contact_email;
        
        //send email
        Mail::to($email)->send(new FeedbackMail($request->contact_email, $request->contact_title, $request->contact_feedback));
        
        return true;
    }
}
