<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;

class HomePageController extends Controller
{
    public function index(){

        $posts = App\Post::where('visibility','=','1')->orderBy('date', 'desc')->paginate(15);

        foreach($posts as $post){
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;
        }

        return view('home', compact('posts'));
    }
}
