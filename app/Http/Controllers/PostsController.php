<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;

class PostsController extends Controller
{
    public function show_post($id){

        $post = App\Post::find($id);
        return view('post', compact('post'));

    }
}
