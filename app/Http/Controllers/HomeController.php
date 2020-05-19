<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class HomeController extends Controller

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

    public function about(){
        $content = App\Settings::get()[0]->about_content;
        return view('about',compact('content'));
    }
}
