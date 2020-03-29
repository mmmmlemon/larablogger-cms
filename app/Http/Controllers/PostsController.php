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
            
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;
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

    public function show_edit_post($id){

        $post = App\Post::find($id);
        $categories = App\Category::all();

        return view('control_panel/edit_post', compact('post','categories'));
    }

    public function edit_post(Request $request, $id){
        
        $request->validate([
            'post_title' => 'string|max:35',
            'post_content' => 'string',
            'publish' => 'string'
        ]);

        $post = App\Post::find($id);
        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        $post->tags = $request->tags;
        $post->category_id = $request->category;

        if($request->publish == 'on'){
            $post->visibility = 1;
        } else {
            $post->visibility = 0;
        }
        $post->save();
        return redirect(url('/control/posts'));
    }
} 

