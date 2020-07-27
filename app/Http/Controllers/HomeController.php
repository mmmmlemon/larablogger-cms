<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //view the index page of the website
    public function index()
    {   
        return view('home');
    }

    //set 'view_type' cookie
    public function setCookie(Request $request)
    {   
        $value = $request->cookie('view_type');

        if($request->change_view == null)
        {
            if($value == null)
            {
                $view_type = App\Settings::all()[0]->view_type;
                $response = new Response("The 'view_type' cookie has been set.");
                $response->withCookie(cookie('view_type', $view_type, $view_type));
                return $response;
            }
            else
            {
                $response = new Response("The 'view_type' cookie was already set.");
                return $response;
            } 
        }
        if($request->change_view == true)
        {
            $response = new Response("The 'view_type' cookie has been set.");
            $response->withCookie(cookie('view_type', $request->view_type, $request->view_type));
            return $response;
        }
    }

    //check if its first visit
    public function check_first_visit(Request $request)
    {
        $value = $request->cookie('visitedBefore');
        if($value == null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function set_first_visit(Request $request)
    {
        $response = new Response("The 'visitedBefore' cookie has been set.");
        $response->withCookie(cookie('visitedBefore', true, true));
        return $response;
    }

    //view About page
    public function about()
    {
        $content = App\Settings::get()[0]->about_content;
        $settings = App\Settings::all()->first();

        if($settings->show_about == 0)
        {
            return redirect()->back();
        }

        //if current user is Admin, the page will also show 'Edit About page' button
        $is_admin = false;
        if(Auth::check())
        {
            if(Auth::user()->user_type == 0)
            {
                $is_admin = true;
            }
        }

        return view('about',compact('content','is_admin'));
    }
}
