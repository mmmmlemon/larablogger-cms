<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use Auth;
use Carbon\Carbon;
use Validator;

class PostsController extends Controller
{

    public function index(){

        $posts = App\Post::where('visibility','=','1')->orderBy('date', 'desc')->orderBy('id','desc')->paginate(15);

        foreach($posts as $post){
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;
            $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->get());
        }

        return view('home', compact('posts'));
    }


    public function show_post($id)
    {
        $post = App\Post::find($id);
        //если такой пост существует, то выводим его
        if($post != null)
        {   
            //получаем юзернейм, если юзер залогинен
            if(Auth::check())
            {$username = Auth::user()->name;}
            else
            {$username="";}

            $comments = App\Comment::where('post_id','=',$id)->orderBy('date','asc')->orderBy('id','asc')->get();

            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;


            //проверяем статус поста, если visibility == 0
            //то пост будем видимым только для админа
            if($post->visibility == 1)
            {   
        
                return view('post', compact('post','username','comments'));
            }
            else
            {
                if(Auth::user()){
                    if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                    {   
                        return view('post', compact('post','username','comments'));
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
        $categories = App\Category::where('category_name','!=','blank')->get();

        return view('control_panel/posts/edit_post', compact('post','categories'));
    }

    public function show_create_post(){
        $current_date = Carbon::now();
        $categories = App\Category::where('category_name','!=','blank')->get();
        return view('control_panel/posts/create_post', compact('categories','current_date'));
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


    public function show_posts_by_tag($tag){

        $posts = App\Post::where('visibility','=','1')->where('tags','like',"%".$tag."%")->orderBy('date', 'desc')->orderBy('id','desc')->paginate(15);

        foreach($posts as $post){
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;
        }

        return view('home', compact('posts'));
    }

    public function create_post(Request $request)
    {
        $request->validate([
            'post_title' => 'string|max:35',
            'post_content' => 'string',
            'publish' => 'string',
            'publish_date' => 'date|after:yesterday'
        ]);

        $post = new App\Post;
        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        $post->category_id = $request->category;
        $post->tags = $request->tags;

        //если чекбокс Publish отмечен, то устанавливаем дату публикации - сегодня
        //если нет, то ту дату которая указана в поле с датой
        if($request->publish == 'on'){
            $post->visibility = 1;
            $post->date = Carbon::now()->format('Y-m-d');
        } else {
            $post->visibility = 1;
            $post->date = $request->publish_date;
        }

       $post->save();
       return redirect(url('/control'));
    }


    public function change_post_status($id, $status)
    {
        $post = App\Post::find($id);    

        $stat = 0;

        if($status == 1)
        {$stat = 1;}

        if($post->visibility != $stat)
        {
            $post->visibility = $stat;
            $post->save();
        }
        else{
            abort(403);
        }

        return redirect(url()->previous());

    }

    public function delete_post($id)
    {
        $post = App\Post::find($id);
        $post->delete();
        return redirect(url()->previous());

    }

    public function submit_comment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:25',
            'comment_content' => 'required|max:1000'
        ]);

        //если валидатор фейлит, то редиректим назад с якорем
        if($validator->fails()){
            return redirect(url()->previous() . "#comment_form")->withErrors($validator)->withInput();
        }
        $comment = new App\Comment;

        $comment->username = $request->username;
        $comment->comment_content = $request->comment_content;
        $comment->post_id = $id;
        $comment->date = Carbon::now();
        $comment->save();
        return redirect(url()->previous());
    }

} 

