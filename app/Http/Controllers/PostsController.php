<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use Auth;

class PostsController extends Controller
{
    public function show_post($id)
    {
        $post = App\Post::find($id);
        //если такой пост существует, то выводим его
        if($post != null)
        {
            //проверяем статус поста, если visibility == 0
            //то пост будем видимым только для админа
            if($post->visibility == 1)
            {
                return view('post', compact('post'));
            }
            else
            {
                if(Auth::user()){
                    if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                    {
                        return view('post', compact('post'));
                    } 
                    else
                    {
                        return abort(404);
                    }
                }
                else
                {
                    return abort(404);
                }
            } 
        }
        else{
            return abort(404);
        }
    }
} 

