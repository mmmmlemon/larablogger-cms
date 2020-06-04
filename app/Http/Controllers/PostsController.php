<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use Auth;
use Carbon\Carbon;
use Validator;
use Storage;
use File;

class PostsController extends Controller
{   
    //КОНТРОЛЬНАЯ ПАНЕЛЬ
    //вывод постов в меню постов
    public function show_list_of_posts()
    {
        $posts = App\Post::orderBy('date','desc')->orderBy('id','desc')->paginate(10);
        $page='normal';
        return view('control_panel/posts/posts', compact('posts','page'));
    }

    //вывод постов в меню постов по дате
    public function show_list_of_posts_by_date()
    {
        $posts = App\Post::orderBy('date','asc')->paginate(10);
        $page = 'date_desc';
        return view('control_panel/posts/posts', compact('posts', 'page'));
    }

    //показать страницу создания поста
    public function show_create_post()
    {
        $current_date = Carbon::now();
        $categories = App\Category::where('category_name','!=','blank')->get();
        return view('control_panel/posts/create_post', compact('categories','current_date'));
    }

    //создание (сохранение) поста
    public function create_post(Request $request)
    {
        $request->validate([
            'post_title' => 'string|max:35',
            'post_content' => 'string',
            'post_visibility' => 'string',
            'post_date' => 'date|after:yesterday'
        ]);

        //создаем новый пост и набиваем его данными
        $post = new App\Post;
        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        $post->category_id = $request->post_category;
        if($request->tags == "")
        {$post->tags =  NULL;}
        else
        {$post->tags = $request->tags;}
        //если чекбокс Publish отмечен, то устанавливаем дату публикации - сегодня
        //если нет, то ту дату которая указана в поле с датой
        
        if($request->post_visibility == "true"){//true - строка, потому что ajax передает строку
            $post->visibility = 1;
            $post->date = $request->post_date;
        } else {
            $post->visibility = 0;
            $post->date = $request->post_date;
        }

        //получаем список файлов в папке temp
        $temp_files = json_decode($request->file_list);
        
        if(count($temp_files) == 0)
        {   
            //если в папке временных файлов нет ни одного файла, т.е
            //юзер не загружал файлы с постом, то просто сохраняем пост
            $post->save(); 
        }
        else //иначе переносим файлы из temp в новую папку и сохраняем пост
        {
            //создаем папку для медиа файлов ассоциируемых с постом
            //posts/[date + post_title]
            $folder_name = date('d-m-Y')."-".$request->post_title;
            $folder_created= Storage::disk('public')->makeDirectory("posts\\". $folder_name);
            //если папка создалась, то перемещаем файлы из temp
            if($folder_created == true)
            {
                foreach($temp_files as $file){
                    //путь по которому будет перемещен файл
                    $new_path = storage_path("app\\public\\posts\\").$folder_name."\\".$file->filename;
                    $move = File::move(storage_path("app\\public\\temp\\").$file->filename, $new_path);
                
                    //если переместить файл не удалось, то редиректим с ошибкой
                    if($move != true) 
                    {return Redirect::back()->withErrors(['err', 'Something went wrong while moving the files.']);}
                    else
                    {   
                    //сохраняем пост перед сохранением медиа (чтобы был $post->id)
                    $post->save();
                    $mime = substr(File::mimeType($new_path), 0, 5); //получаем mime-тип файла
                    $media = new App\Media; //создаем запись о медиа и сохраняем
                    $media->post_id = $post->id;
                    $media->media_url = "posts/". $folder_name."/".$file->filename;
                    $media->media_type = $mime;
                    $media->display_name = $file->filename;
                    $media->actual_name = $file->filename;
                    $media->visibility = 1;
                    $media->save(); 
                    }
                }         
            }
        }
    
        return response()->json(['msg'=>'The post "'.$request->post_title.'" has been created.']);
    }


    //показать страницу редактирования поста
    public function show_edit_post($id)
    {
        $post = App\Post::find($id);

        //список категорий без "пустой" категории
        $categories = App\Category::where('category_name','!=','blank')->get();
        $media = App\Media::where('post_id','=',$id)->get();

        foreach($media as $m)
        {
            $url = $m->media_url;
            $substr = strrchr($url, "/");
            $filename = substr($substr,1, strlen($substr));
            $m->filename = $filename;
        }

        return view('control_panel/posts/edit_post', compact('post','categories', 'media'));
    }
  
    //редактировать пост, сохранение измененений
    public function edit_post(Request $request, $id)
    {
        $request->validate([
            'post_title' => 'string|max:35',
            'post_content' => 'string|max:10000',
            'post_visibility' => 'string',
            'tags' => 'string'
        ]);
            
        $post = App\Post::find($id);
        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        $post->category_id = $request->post_category;

        if($request->tags == "")
        {$post->tags =  NULL;}
        else
        {$post->tags = $request->tags;}

        if($request->post_visibility == "true")
        { //true - строка, потому что ajax передает строку
            $post->visibility = 1;
        } 
        else 
        {
            $post->visibility = 0;
        }

        //получаем список файлов в папке temp
        $temp_files = json_decode($request->file_list);
  
        if(count($temp_files) == 0) //если там нет файлов, то никакие файлы не были добавлены, сохраняем пост
        {$post->save();}
        else //если есть, то переносим файлы из temp в папку с медиафайлами поста и после этого сохраняем пост
        {
            $folder_name = date("d-m-Y", strtotime($post->date))."-".$post->post_title;
            //проверяем была ли создана папка для файлов текущего поста
            $check = File::exists(storage_path("app\\public\\posts\\".$folder_name));
            
            if($check != true)
            {
                Storage::disk('public')->makeDirectory("posts\\". $folder_name);
            }

            foreach($temp_files as $file)
            {
                //путь по которому будет перемещен файл
                $new_path = storage_path("app\\public\\posts\\").$folder_name."\\".$file;
                
                $move = File::move(storage_path("app\\public\\temp\\".$file), $new_path);
            
                //если переместить файл не удалось, то редиректим с ошибкой
                if($move != true) 
                {return Redirect::back()->withErrors(['err', 'Something went wrong while moving the files.']);}
                else
                {   
                    //сохраняем пост перед сохранением медиа (чтобы был $post->id)
                    $post->save();
                    $mime = substr(File::mimeType($new_path), 0, 5); //получаем mime-тип файла
                    $media = new App\Media; //создаем запись о медиа и сохраняем
                    $media->post_id = $post->id;
                    $media->media_url = "posts/". $folder_name."/".$file;
                    $media->media_type = $mime;
                    $media->display_name = $file;
                    $media->actual_name = $file;
                    $media->visibility = 1;
                    $media->save(); 
                }
            }             
        }
        
        return response()->json(['msg' => 'The changes have been saved to post "'.$request->post_title.'"']);
    }

    //загрузка файлов перед сохранением поста
    public function upload_files(Request $request)
    {  
       //получаем имя файла
       $filename = $request->filename;

       $uuid8 = substr($request->dzuuid, 0, 7);
       $p = pathinfo($filename);
       $ext = $p['extension'];
       $name = $p['filename'];
       $filename = $name."-".$uuid8.".".$ext;
       
       //создаем файл в нужной папке, и открываем его в режиме append
       $file = fopen(storage_path('app\\public\\temp\\')."$filename","a");

       //вставляем содержимое файла\чанк в открытый файл и закрываем\сохраняем
       fputs($file,file_get_contents($request->file));
       fclose($file);
       return response()->json([
        'file_url' => asset("storage/temp/".$filename),
        'filename' =>  $filename,
        'mime' => substr(File::mimeType(storage_path('app\\public\\temp\\')."$filename"),0,5)
        ]);
    }	    

    //очистить папку temp
    public function clear_temp()
    {
        $temp_files = File::files(storage_path("app\\public\\temp"));
        foreach($temp_files as $file)
        {
            unlink($file->getPathname());
        }
    }

    //удаление файла из поста
    public function delete_media(Request $request)
    {
        //если intval равен 0 значит вместо id было передано имя файла
        //это значит что в пост во время редактирования был добавлен новый файл и пользователь решил его удалить
        //т.к файл еще не прописан в БД, то вместо id передается его имя и при удалении мы просто удаляем его из temp по имени
        if(intval($request->id) == 0)
        {   
            $filename = $request->id; //чтобы было чуть лаконичнее, записываем имя файла в переменную
            
            if(is_file(storage_path("app\\public\\temp\\".$filename)))
            {
                $check = unlink(storage_path("app\\public\\temp\\".$filename));
                if($check == true) {return response()->json(['msg'=> $filename . " has been deleted from 'Temp'."]);}
            }
        }

        //если же был передан id, значит этот файл уже был добавлен ранее и он прописан в БД
        //мы удаляем как сам файл, так и саму запись о нём в базе данных по id
        else
        {
            $media = App\Media::find($request->id);
            $check_delete = unlink(storage_path("app\\public\\").$media->media_url);
            if($check_delete == true) 
            {
                $post = App\Post::find($media->post_id);
                $folder_name = date("d-m-Y",strtotime($post->date))."-".$post->post_title;
                $files = File::files(storage_path("app\\public\\posts\\".$folder_name));
                if(count($files)== 0)
                {   
                    File::deleteDirectory(storage_path("app\\public\\posts\\").$folder_name);
                }
                
                $media->delete();

                return response()->json(['result'=>'success']); 
            }
            else { return response()->json(['result'=>'failure']);}
        }

    }

    //ВЫВОД
    //вывод постов на главной странице сайта
    public function index()
    {
        //получаем список всех видимых постов и сортируем его по дате по убыванию
        $posts = App\Post::where('visibility','=','1')->where('date','<=',Carbon::now()->format('Y-m-d'))->orderBy('pinned','desc')->orderBy('date', 'desc')->orderBy('id','desc')->paginate(15);

        foreach($posts as $post)
        {
            //получаем теги поста и прикрепляем к посту
            $tags= explode(",", $post->tags);
            //если в теге записан один пустой символ то делаем теги = null
            if(count($tags) == 1 && $tags[0]=="")
            {$post->tags = null;}
            else
            {$post->tags = $tags;}

            //прикрепляем название категории к посту
            $post->category = App\Category::find($post->category_id)->category_name;

            if($post->category == "blank") //если это пустая категория, то категория не будет выводиться
            {$post->category = "";}

            //считаем количество комментариев у поста
            $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());

            //если комментариев больше одного то выводим commentS, а не comment
            if($post->comment_count > 1 || $post->comment_count == 0)
            {
                $post->comment_count .= " comments"; 
            } 
            else 
            {
                $post->comment_count .= " comment"; 
            }

            //получаем список медиа файлов у поста
            $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();
            
            //если файлы есть
            if(count($media) != 0)
            {
                $post->media = $media; //
                $post->media_type = $media[0]->media_type;
            }
        }

        //для вывода постов по тегам
        //установливаем имя тега - null, чтобы когда выводятся все посты на главной
        //не было ошибки
        $tag_name = null;
        return view('home', compact('posts', 'tag_name'));
    }

    //показать пост
    public function show_post($id)
    {
        //получить пост по id
        $post = App\Post::find($id);
        //получаем медиа
        $media = App\Media::where('post_id',$id)->where('visibility','=',1)->orderBy('media_type','asc')->orderBy('id','asc')->get();

        $is_admin = false;

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
                 return view('post', compact('post','username','comments','media','is_admin'));
            }
            else //если visibility == 0, то пост виден только админу
            {   //проверяем залогинен ли юзер
                if(Auth::check()){
                    //если он админ
                    if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                    {   
                        return view('post', compact('post','username','media','comments'));
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

    //показать все посты по тегу N
    public function show_posts_by_tag ($tag)
    {
        $posts = App\Post::where('visibility','=','1')->where('tags','like',"%".$tag."%")->orderBy('date', 'desc')->orderBy('id','desc')->paginate(15);

        foreach($posts as $post){
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;

            $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();
            if(count($media) != 0)
            {
                $post->media = $media;
                $post->media_type = $media[0]->media_type;
            }
        }

        $tag_name = $tag;

        return view('home', compact('posts','tag_name'));
    }

    //изменить видимость поста
    public function change_post_visibility($id, $status)
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

    //удалить пост
    public function delete_post(Request $request)
    {
        //сначала удаляем все комментрии связанные с этим постом
        $comments = App\Comment::where('post_id', $request->modal_form_input)->get();
        foreach($comments as $comment){
            $comment->delete();
        }
        //и медиа файлы тоже
        $media = App\Media::where('post_id', $request->modal_form_input)->get();
        foreach($media as $m)
        {
            $pos = strripos($m->media_url,"/");
            $path = substr($m->media_url, 0, $pos);
            File::deleteDirectory(storage_path('app\\public\\'.$path));
            $m->delete();
        }

        $post = App\Post::find($request->modal_form_input);
        $post->delete();
        return redirect(url()->previous());
    }

    //отправить комментарий
    public function submit_comment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:25',
            'comment_content' => 'required|max:1000'
        ]);

        //если валидатор фейлит, то редиректим назад с якорем
        if($validator->fails())
        {
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

    //спрятать/показать/удалить комментарий
    public function change_comment_status(Request $request)
    {
        $comment = App\Comment::find($request->comment_id);

        if($request->action == "hide")
        {
            $comment->visibility = 0;
            $comment->save();
        }
        else if($request->action == "show")
        {
            $comment->visibility = 1;
            $comment->save();
        }
        else if($request->action == "delete")
        {
            $comment = App\Comment::find($request->comment_id);
            $comment->delete();
        }
        else{
            return redirect(url()->previous());
        }
        
        return redirect(url()->previous());
    }

    //закрепить\открепить пост
    public function pin_post(Request $request)
    {
        $post = App\Post::find($request->id);

        if($post->pinned == 0)
        {
            $post->pinned = 1;
        }
        else{
            $post->pinned = 0;
        }

        $post->save();

        return redirect()->back();
    }

} 

