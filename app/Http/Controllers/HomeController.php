<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Illuminate\Http\Response;
use App\Globals\Globals;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

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
    public function set_view_type(Request $request)
    {   
        //get current view type from cookies
        $current_view_type = $request->cookie('view_type');

        //if user visits the web site for the first time, set view_type cookie
        if($request->change_view == null)
        {   
            //if cookie was not set
            if($current_view_type == null)
            {   
                //get default view_type from settings
                $view_type_by_default = App\Settings::all()[0]->view_type;
                
                $response = new Response("The 'view_type' cookie has been set.");

                //set cookie
                $response->withCookie(cookie('view_type', $view_type_by_default, $view_type_by_default));

                return $response;
            }
            else
            {   
                //if value is null, do not set the cookie
                $response = new Response("The 'view_type' cookie was already set.");
                return $response;
            } 
        }

        //if it's not users first time and he wants to change the type of view
        if($request->change_view == true)
        {   
            $response = new Response("The 'view_type' cookie has been set.");
            
            //set cookie
            $response->withCookie(cookie('view_type', $request->view_type, $request->view_type));
            return $response;
        }
    }

    //check if user has accepted the cookies before
    public function check_cookies_accepted(Request $request)
    {   
        //if cookie 'visitedBefore' does not exist, this is the first visit
        $cookies_accepted = $request->cookie('cookiesAccepted');

        if($cookies_accepted == null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //set cookie 'cookiesAccepted' if user has acceped the cookies
    public function set_cookies_accepted(Request $request)
    {
        $response = new Response("The 'cookiesAccepted' cookie has been set.");

        $response->withCookie(cookie()->forever('cookiesAccepted', true));

        return $response;
    }

    //view 'About' page
    public function view_about_page()
    {
        $about_content = App\Settings::get()[0]->about_content;

        //if 'About' page set is not visible, redirect back
        if(config('settings')->show_about == 0)
        {
            return redirect()->back();
        }

        //if current user is Admin, the page will also show 'Edit About page' button
        $is_admin = Globals::check_admin();

        return view('about',compact('about_content','is_admin'));
    }
}
