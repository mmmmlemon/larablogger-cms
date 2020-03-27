<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;

class HomePageController extends Controller
{
    public function index(){

        $posts = App\Post::where('visibility','=','1')->orderBy('date', 'desc')->get();

        return view('home', compact('posts'));
    }
}
