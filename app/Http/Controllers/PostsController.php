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

        $posts = App\Post::where('visibility','=','1')->where('date','<=',Carbon::now()->format('Y-m-d'))->orderBy('date', 'desc')->orderBy('id','desc')->paginate(15);

        foreach($posts as $post){
            //получаем теги поста
            $tags= explode(",", $post->tags);
            if(count($tags) == 1 && $tags[0]=="")
            {$post->tags = null;}
            else
            {$post->tags = $tags;}
            $post->category = App\Category::find($post->category_id)->category_name;
            if($post->category == "blank")
            {$post->category = "";}
            $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());
            if($post->comment_count > 1 || $post->comment_count == 0)
            {
                $post->comment_count .= " comments"; 
            } else {
                $post->comment_count .= " comment"; 
            }
       
        }

        return view('home', compact('posts'));
    }


    public function show_post($id)
    {
        $post = App\Post::find($id);
        //если такой пост существует, то выводим его
        if($post != null)
        {   
            //если юзер залогинен
            if(Auth::check())
            {   
                $username = Auth::user()->name; //получаем его юзернейм
                //если юзер - админ
                if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                {$is_admin = true;} //то указываем что он админ
                else  //или не админ
                {$is_admin == false;}
            }
            else //если не залогинен, то юзернейм пустой, а не админ
            {
                $username="";
                $is_admin = false;
            }

            //получаем комменты к посту
            if($is_admin == true) //если админ, то получаем все комменты, если нет то только видимые
            {$comments = App\Comment::where('post_id','=',$id)->orderBy('date','asc')->orderBy('id','asc')->get();}
            else
            {$comments = App\Comment::where('post_id','=',$id)->where('visibility','=',1)->orderBy('date','asc')->orderBy('id','asc')->get();}
           
            //считаем количество комментов
            $post->comment_count = count($comments);

            //если комментов больше одного, или их ноль
            if($post->comment_count > 1 || $post->comment_count == 0)
            {$post->comment_count .= " comments";}  //тогда приписка будет comments
            else
            {$post->comment_count .= " comment";} //или comment

            //получаем теги поста
            $tags= explode(",", $post->tags);
            if(count($tags) == 1 && $tags[0]=="")
            {$post->tags = null;}
            else
            {$post->tags = $tags;}

            //получаем категорию поста
            $post->category = App\Category::find($post->category_id)->category_name;
            //если категория "blank", то не будем выводить её название
            if($post->category == "blank")
            {$post->category = "";}
           
            //проверяем статус поста, если visibility == 1, то пост виден всем без исключения
            if($post->visibility == 1)
            {   
                 return view('post', compact('post','username','comments','is_admin'));
            }
            else //если visibility == 0, то пост виден только админу
            {   //проверяем залогинен ли юзер
                if(Auth::check()){
                    //если он админ
                    if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                    {   
                        return view('post', compact('post','username','comments'));
                    } 
                    else //если залогинен, но не админ, то 404
                    {
                        return abort(404);
                    }
                }
                else //если не залогинен вообще, то 404
                {
                    return abort(404);
                }
            } 
        }
        else //если поста не существует, то 404
        {
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
            'post_content' => 'string|max:10000',
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
        if($request->tags == "")
        {$post->tags =  NULL;}
        else
        {$post->tags = $request->tags;}
        

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
       return redirect(url('/control/posts'));
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

    public function delete_post(Request $request)
    {
        //сначала удаляем все комментрии связанные с этим постом
        $comments = App\Comment::where('post_id', $request->modal_form_input)->get();
        foreach($comments as $comment){
            $comment->delete();
        }
        $post = App\Post::find($request->modal_form_input);
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

    public function hide_comment(Request $request){
        $comment = App\Comment::find($request->comment_id);
        $comment->visibility = 0;
        $comment->save();
        return redirect(url()->previous());
    }

    public function show_comment(Request $request){
        $comment = App\Comment::find($request->comment_id);
        $comment->visibility = 1;
        $comment->save();
        return redirect(url()->previous());
    }
} 

