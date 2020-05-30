<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;

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

    //вывод главной страницы сайта
    public function index()
    {
        return view('home');
    }

    //показать страницу About
    public function about()
    {
        $content = App\Settings::get()[0]->about_content;
        $is_admin = false;
        $settings = App\Settings::all()->first();

        if($settings->show_about == 0)
        {
            return redirect()->back();
        }

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
