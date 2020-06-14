<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\FeedbackMail;

use Illuminate\Support\Facades\Mail;

use App;

class FeedbackController extends Controller
{
    public function mail(Request $request)
    {
        //получаем E-mail на который пойдет письмо
        $email = App\Settings::all()->first()->contact_email;
 
        Mail::to($email)->send(new FeedbackMail($request->contact_email,$request->contact_title,$request->contact_feedback));

        return true;
    }
}
