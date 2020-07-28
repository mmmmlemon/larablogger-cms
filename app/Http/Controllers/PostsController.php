<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App;
use Auth;
use Carbon\Carbon;
use Validator;
use Storage;
use File;
use Jenssegers\Agent\Agent;

//functions related to Post contol
class PostsController extends Controller
{   
    //CONTROL PANEL
    //view all posts (date descending)
    public function show_list_of_posts()
    {
        $posts = App\Post::orderBy('date','desc')->orderBy('id','desc')->paginate(10);
        $page='normal';
        return view('control_panel/posts/posts', compact('posts','page'));
    }

    //view all posts (date ascending)
    public function show_list_of_posts_by_date()
    {
        $posts = App\Post::orderBy('date','asc')->paginate(10);
        $page = 'date_desc';
        return view('control_panel/posts/posts', compact('posts', 'page'));
    }

    //view Add Post page
    public function show_create_post()
    {
        $current_date = Carbon::now();
        $categories = App\Category::where('category_name','!=','blank')->orderBy('visual_order','asc')->get();
        return view('control_panel/posts/create_post', compact('categories','current_date'));
    }

    //create/save new post
    public function create_post(Request $request)
    {
        $request->validate([
            'post_title' => 'string|max:70',
            'post_content' => 'string|nullable',
            'post_visibility' => 'string',
            'post_date' => 'date|after:yesterday',
             'tags' => 'string|nullable'
        ]);

        //create a new post and fill it with data
        $post = new App\Post;
        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        $post->category_id = $request->post_category;
        if($request->tags == "")
        {$post->tags =  NULL;}
        else
        {$post->tags = $request->tags;}
        //if "Publish" checkbox is checked, then the date of publishing will be set to current date
        //in other case, the date of publishing will be date to the value of date field
        
        if($request->post_visibility == "true"){//true is a string, because ajax sends string variables
            $post->visibility = 1;
            $post->date = $request->post_date;
        } else {
            $post->visibility = 0;
            $post->date = $request->post_date;
        }

        //get list of files in temp folder
        $temp_files = json_decode($request->file_list);
        
        if(count($temp_files) == 0)
        {   
            //if temp folder is empty, which means the user did not upload anything
            //then just save the post
            $post->save(); 
        }
        else //else, replace all the files from the temp folder to the new folder, and then save the post
        {
            //create new folder for media files associated with the post
            //posts/[date + post_title]
            $folder_name_old = date('d-m-Y')."-".$request->post_title;
            $folder_name = preg_replace("/[^a-zA-Z0-9\-\s]/", "", $folder_name_old);
            $folder_created= Storage::disk('public')->makeDirectory("posts/". $folder_name);
            //if folder crated, replace files from temp
            if($folder_created == true)
            {
                foreach($temp_files as $file){
                    //path for replacement
                    $new_path = storage_path("app/public/posts/").$folder_name."/".$file->filename;
                    $move = File::move(storage_path("app/public/temp/").$file->filename, $new_path);
                
                    //if replacement failed, redirect with error
                    if($move != true) 
                    {return Redirect::back()->withErrors(['err', 'Something went wrong while moving the files.']);}
                    else
                    {   
                        //save the post before saving the media (to extract $post->id)
                        $post->save();
                        $mime = substr(File::mimeType($new_path), 0, 5); //get mime-type of file
                        $media = new App\Media; //write media file into the database
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


    //view Edit Post page
    public function show_edit_post($id)
    {
        $post = App\Post::find($id);

        //categories list (without the 'blank' category)
        $categories = App\Category::where('category_name','!=','blank')->orderBy('visual_order','asc')->get();
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
  
    //save changes to the edited post
    public function edit_post(Request $request, $id)
    {
        $request->validate([
            'post_title' => 'string|max:70',
            'post_content' => 'string|max:100000',
            'post_visibility' => 'string',
            'tags' => 'string|nullable'
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
        { //true is a string type, because ajax sends strings
            $post->visibility = 1;
        } 
        else 
        {
            $post->visibility = 0;
        }

        //get list of files in the temp folder
        $temp_files = json_decode($request->file_list);
  
        if(count($temp_files) == 0) //if temp is empty, this means no files were added, just save the post
        {$post->save();}
        else //if temp is not empty, replace all the files from the temp folder and then save
        {
            $folder_name = date("d-m-Y", strtotime($post->date))."-".$post->post_title;
            //check if folder has been created
            $check = File::exists(storage_path("app/public/posts/".$folder_name));
            
            if($check != true)
            {
                Storage::disk('public')->makeDirectory("posts/". $folder_name);
            }

            foreach($temp_files as $file)
            {
                //path for replacement
                $new_path = storage_path("app/public/posts/").$folder_name."/".$file;
                
                $move = File::move(storage_path("app/public/temp/".$file), $new_path);
            
                //if replacement failed, redirect back with error
                if($move != true) 
                {return Redirect::back()->withErrors(['err', 'Something went wrong while moving the files.']);}
                else
                {   
                    //save the post before saving the media (to extract $post->id)
                    $post->save();
                    $mime = substr(File::mimeType($new_path), 0, 5); //get mime type of media
                    $media = new App\Media; //write media into the database
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

    //upload files before post creation
    public function upload_files(Request $request)
    {  
       //get filename
       $filename = $request->filename;

       $uuid8 = substr($request->dzuuid, 0, 7);
       $p = pathinfo($filename);
       $ext = $p['extension'];
       $name = $p['filename'];
       $filename = $name."-".$uuid8.".".$ext;
       
       //create empty file and append
       $file = fopen(storage_path('app/public/temp/')."$filename","a");

       //insert check into the file and save it
       fputs($file,file_get_contents($request->file));
       fclose($file);
       return response()->json([
        'file_url' => asset("storage/temp/".$filename),
        'filename' =>  $filename,
        'mime' => substr(File::mimeType(storage_path('app/public/temp/')."$filename"),0,5)
        ]);
    }	    

    //clear temp folder
    public function clear_temp()
    {
        $temp_files = File::files(storage_path("app/public/temp"));
        foreach($temp_files as $file)
        {
            unlink($file->getPathname());
        }
    }

    //delete media file from post
    public function delete_media(Request $request)
    {
        //if id is not digit, this means the file was just added into the temp folder and it's not written into database yet
        //the file should be removed from the temp folder
        if(ctype_digit($request->id) == false)
        {   
            $filename = $request->id; 
            
            //check if file exists
            if(is_file(storage_path("app/public/temp/".$filename)))
            {   
                //delete file from temp
                $check = unlink(storage_path("app/public/temp/".$filename));
                if($check == true) {return response()->json(['msg'=> $filename . " has been deleted from 'Temp'."]);} //if succes, return response message
            }
        }
        //if id IS digit, this means the file is written into the database
        //the file should be deleted both physically and from the database
        else
        {
            $media = App\Media::find($request->id);
            
            $check_delete = unlink(storage_path("app/public/").$media->media_url);
            if($check_delete == true) 
            {
                $post = App\Post::find($media->post_id);
                $folder_name = date("d-m-Y",strtotime($post->date))."-".$post->post_title;
                $files = File::files(storage_path("app/public/posts/".$folder_name));
                if(count($files)== 0)
                {   
                    File::deleteDirectory(storage_path("app/public/posts/").$folder_name);
                }
                
                $media->delete();

                return response()->json(['result'=>'success']); 
            }
            else { return response()->json(['result'=>'failure']);}
        }

    }

    //VIEWS
    //view all posts on the main page of the website
    public function index(Request $request)
    {
        $view_type = $request->cookie('view_type');

        $paginate = 9;

        if($view_type == null)
        {
            $view_type = App\Settings::all()[0]->view_type;
        }

        if($view_type == 'grid')
        {
            $paginate = 27;
        }

        //get all the visible posts and sort them by date (desc)
        $posts = App\Post::where('visibility','=','1')->where('date','<=',Carbon::now()->format('Y-m-d'))->orderBy('pinned','desc')->orderBy('date', 'desc')->orderBy('id','desc')->paginate($paginate);

        foreach($posts as $post)
        {
            //get tags of a current post and attach them
            $tags= explode(",", $post->tags);
            //if $tags variable contains one empty character (which means there are no tags for this Post)
            if(count($tags) == 1 && $tags[0]=="")
            {$post->tags = null;} //make it null
            else
            {$post->tags = $tags;}

            //attach the name of the Category to current Post 
            $post->category = App\Category::find($post->category_id)->category_name;

            if($post->category == "blank") //if it is the 'blank' category, it won't be displayed
            {$post->category = "";}

            //count comments in current Post
            $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());

            //if theres more than one comment
            if($post->comment_count > 1 || $post->comment_count == 0)
            {
                $post->comment_count .= " comments"; //the label will be commentS
            } 
            else 
            {
                $post->comment_count .= " comment"; //else, commenT
            }

            //get the list of files for current Post
            $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();
            
            //if current Post has files
            if(count($media) != 0)
            {
                //add subtitles for each file
                foreach($media as $m)
                {
                    $subs = App\Subtitles::where('media_id','=',$m->id)->where('visibility','=','1')->orderBy('display_name','asc')->get();
                    $m->subs = $subs;
                }

                //attach media files to current Post
                $post->media = $media;
                //attach media_type to current Post
                $post->media_type = $media[0]->media_type;
            }

        }

        //for sorting by tag
        //set tag name to null to avoid error when posts aren't sorted by tag
        $tag_name = null;
        
        return view('home', compact('posts', 'tag_name', 'view_type'));
    }

    //view post
    public function show_post($id)
    {
        //get post by id
        $post = App\Post::find($id);
        //get media files
        $media = App\Media::where('post_id',$id)->where('visibility','=',1)->orderBy('media_type','asc')->orderBy('id','asc')->get();

        foreach($media as $m)
        {
            $subs = App\Subtitles::where('media_id','=',$m->id)->where('visibility','=','1')->orderBy('display_name','asc')->get();
            $m->subs = $subs;
        }

        //if post exists, view it
        if($post != null)
        {   
            //if the user is logged in
            if(Auth::check())
            {   
                $username = Auth::user()->name; //get username
                //if user is Admin
                if(Auth::user()->user_type == 1 || Auth::user()->user_type == 0)
                {$is_admin = true;} //user is Admin
                else  //user is not Admin
                {$is_admin = false;}
            }
            else //if user is not logged in, the username is empty and the user is not Admin
            {
                $username="";
                $is_admin = false;
            }

            //get comments for Posts
            if($is_admin == true) //if user is admin, get all of the comments, if not, get only visible comments
            {
                $comments = App\Comment::where('post_id','=',$id)->where('reply_to','=',null)->orderBy('date','asc')->orderBy('id','asc')->get();
            }
            else
            {
                $comments = App\Comment::where('post_id','=',$id)->where('reply_to','=',null)->where('visibility','=',1)->orderBy('date','asc')->orderBy('id','asc')->get();
            }

           
            //$comments = App\Comment::where('post_id','=',$id)->where('reply_to','=',null)->orderBy('date','asc')->orderBy('id','asc')->get();

            //recursive function that collects all the comments with replies and generates a comment 
            function collect_comments($array, $admin)
            {
                foreach($array as $a)
                {   
                    if($admin == true)
                    {
                        $replies = App\Comment::where('reply_to','=', $a->id)->get();
                        $a->replies = $replies;
                        collect_comments($replies, $admin);
                    }
                    
                    else{
                        $replies = App\Comment::where('reply_to','=', $a->id)->where('visibility','=',1)->get();
                        $a->replies = $replies;
                        collect_comments($replies, $admin);
                    }
                } 
            }

            collect_comments($comments, $is_admin);

            //count comment
            if($is_admin == true)
            {$post->comment_count = count(App\Comment::where('post_id','=',$id)->get());}
            else
            {$post->comment_count = count(App\Comment::where('post_id','=',$id)->where('visibility','=',1)->get());}


            //if theres more than one comment
            if($post->comment_count > 1 || $post->comment_count == 0)
            {$post->comment_count .= " comments";}  //commentS
            else
            {$post->comment_count .= " comment";} //or commenT

            //gets tags for post
            $tags= explode(",", $post->tags);
            if(count($tags) == 1 && $tags[0]=="")
            {$post->tags = null;}
            else
            {$post->tags = $tags;}

            //get category of the post
            $post->category = App\Category::find($post->category_id)->category_name;
            //if it's 'blank', it won't be displayed
            if($post->category == "blank")
            {$post->category = "";}
           
            //check post status, if visibility == 1, the post is visible to everyone
            if($post->visibility == 1)
            {   
                 return view('post', compact('post','username','comments','media','is_admin'));
            }
            else //if visibility == 0, the post is only visible to Admin
            {   //check if user is logged in
                if(Auth::check()){
                    //if its Admin
                    if(Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
                    {   
                        return view('post', compact('post','username','media','comments','is_admin'));
                    } 
                    else //if logged in but not Admin, throw 404
                    {
                        return abort(404);
                    }
                }
                else //if not logged in at all, throw 404
                {
                    return abort(404);
                }
            } 
        }
        else //if post doesn't exist, throw 404
        {
            return abort(404);
        }
    }

    //view all posts by tag
    public function show_posts_by_tag ($tag, Request $request)
    {
        $posts = App\Post::where('visibility','=','1')->where('tags','like',"%".$tag."%")->orderBy('date', 'desc')->orderBy('id','desc')->paginate(7);

        $view_type = $request->cookie('view_type');

        if($view_type == null)
        {
            $view_type = App\Settings::all()[0]->view_type;
        }

        foreach($posts as $post){
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;

            //attach category name to post
            $post->category = App\Category::find($post->category_id)->category_name;

            //count comments
            $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());

            if($post->comment_count > 1 || $post->comment_count == 0)
            {
                $post->comment_count .= " comments"; 
            } 
            else 
            {
                $post->comment_count .= " comment"; 
            }


            $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();
            if(count($media) != 0)
            {
                foreach($media as $m)
                {
                    $subs = App\Subtitles::where('media_id','=',$m->id)->where('visibility','=','1')->get();
                    $m->subs = $subs;
                }

                $post->media = $media;
                $post->media_type = $media[0]->media_type;
            }
        }

        $tag_name = $tag;

        return view('home', compact('posts','tag_name', 'view_type'));
    }

    //change post visibility
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

    //delete post
    public function delete_post(Request $request)
    {
        //delete all comments associated with this post
        $comments = App\Comment::where('post_id', $request->modal_form_input)->get();
        foreach($comments as $comment){
            $comment->delete();
        }
        //delete all files associated with this post
        $media = App\Media::where('post_id', $request->modal_form_input)->get();

        foreach($media as $m)
        {
            if($m->media_type == "video")
            {
                $subs = App\Subtitles::where('media_id',$m->id)->get();

                foreach($subs as $s)
                {
                    $s->delete();
                }
            }
            
            $pos = strripos($m->media_url,"/");
            $path = substr($m->media_url, 0, $pos);
            File::deleteDirectory(storage_path('app/public/'.$path));
            $m->delete();
        }

        $post = App\Post::find($request->modal_form_input);
        $post->delete();
        return redirect(url()->previous());
    }

    //submit comment
    public function submit_comment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:25',
            'comment_content' => 'required|max:1000'
        ]);

        if($validator->fails())
        {
            return redirect(url()->previous() . "#comment_form")->withErrors($validator)->withInput();
        }

        $comment = new App\Comment;

        $comment->username = $request->username;
        $comment->comment_content = $request->comment_content;
        $comment->post_id = $id;
        $comment->date = Carbon::now();
        $comment->reply_to = $request->reply_to;
        if(Auth::check())
        {
            $comment->is_logged_on = 1;            
        }
        else
        {
            $comment->is_logged_on = 0;
        }

        $comment->save();

        $latest_comment_id = App\Comment::orderBy('id','desc')->first()->id;

        return redirect(url()->previous()."#comment_anchor_".$latest_comment_id);
    }

    //show/hide/delete comment (for Admin)
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

    //pin/unpin post
    public function pin_post(Request $request)
    {
        $post = App\Post::find($request->id);

        if($post->pinned == 0)
        {
            $post->pinned = 1;
        }
        else
        {
            $post->pinned = 0;
        }

        $post->save();

        return redirect()->back();
    }

    //search post function
    public function search_post(Request $request)
    {   
        $val = $request->value;

        $result = DB::table('posts')->select('id','post_title','post_content')->where('post_title','like','%'.$val.'%')->orWhere('post_content','like','%'.$val.'%')->get();

        foreach($result as $r)
        {
            $r->post_content = strip_tags($r->post_content);
        }

        return json_encode($result);
    }

} 

