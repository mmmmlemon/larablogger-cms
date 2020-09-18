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
use App\Globals\Globals;

//functions related to Post contol
class PostsController extends Controller
{   
    //CONTROL PANEL
    //view all posts page (date descending)
    public function view_posts_page()
    {
        $posts = App\Post::orderBy('date','desc')->orderBy('id','desc')->paginate(10);

        $page='date_desc';

        if($posts != null)
        { return view('control_panel/posts/posts', compact('posts','page')); }
        else
        { return abort (500, "Couldn't get posts from the database."); }
    }

    //view all posts page (date ascending)
    public function view_posts_page_asc()
    {
        $posts = App\Post::orderBy('date','asc')->paginate(10);

        $page = 'date_asc';

        if($posts != null)
        { return view('control_panel/posts/posts', compact('posts', 'page')); }
        else
        { return abort(500, "Couldn't get posts from the database."); }
    }

    //view 'Add Post' page
    public function view_add_post_page()
    {
        $current_date = Carbon::now();

        $categories = App\Category::where('category_name','!=','blank')->orderBy('visual_order','asc')->get();

        if(count($categories) > 0)
        { return view('control_panel/posts/create_post', compact('categories','current_date')); }
        else
        { return abort(500, "Couldn't get category list from the database."); }
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

        //if tags string is empty, set it to null
        if($request->tags == "")
        { $post->tags =  null; }
        else
        { $post->tags = $request->tags; }

        //true is a string, because ajax sends string variables
        if($request->post_visibility == "true")
        {
            $post->visibility = 1;
            $post->date = $request->post_date;
        } 
        else 
        {
            $post->visibility = 0;
            $post->date = $request->post_date;
        }

        //true is a string type, because ajax sends string variables
        if($request->post_pinned == "true")
        { $post->pinned = 1; } 
        else 
        { $post->pinned = 0; }

        //get list of files in temp folder
        $temp_files = json_decode($request->file_list);
        
        //if temp folder is empty, which means the user did not upload anything
        //then just save the post
        if(count($temp_files) == 0)
        { $post->save(); }
        //else, replace all the files from the temp folder to the new folder, and then save the post
        else 
        {
            //create new folder for media files associated with the post
            //posts/[date + post_title]
            $folder_name_unfiltered = date('d-m-Y')."-".$request->post_title;
            //leave only letters and numbers
            $folder_name = preg_replace("/[^a-zA-Z0-9\-\s]/", "", $folder_name_unfiltered);
            $folder_created= Storage::disk('public')->makeDirectory("posts/" . $folder_name);

            //if folder created, replace files from temp
            if($folder_created == true)
            {
                foreach($temp_files as $file)
                {
                    //path for replacement
                    $new_path = storage_path("app/public/posts/") . $folder_name . "/" . $file->filename;
                    $move = File::move(storage_path("app/public/temp/") . $file->filename, $new_path);
                
                    //if replacement failed, redirect with error
                    if($move != true) 
                    { return abort(500, "Couldn't move file(s) from 'temp' to '" . $folder_name . "'."); }
                    else
                    {   
                        //save the post before saving the media (to extract $post->id)
                        $post->save();
                        //get mime-type of file
                        $mime = substr(File::mimeType($new_path), 0, 5); 
                        //write media file into the database
                        $media = new App\Media; 
                        $media->post_id = $post->id;
                        $media->media_url = "posts/" . $folder_name . "/" . $file->filename;
                        $media->media_type = $mime;
                        $media->display_name = $file->filename;
                        $media->actual_name = $file->filename;
                        $media->visibility = 1;
                        $media->save(); 
                    }
                }         
            }
            else
            { return abort(500, "Couldn't create folder '".$folder_name."'."); }
        }
    
        return response()->json(['msg'=>'The post "'.$request->post_title.'" has been created.']);
    }

    //view 'Edit Post' page
    public function view_edit_post_page($id)
    {
        $post = App\Post::find($id);

        if($post != null)
        {
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

            if(count($categories) > 0)
            { return view('control_panel/posts/edit_post', compact('post','categories', 'media')); }
            else
            { return abort(500, "Couldn't get categories from the database."); }
        }
        else
        { return abort(500, "Couldn't get the post from the database."); }
    }
  
    //save changes to the edited post
    public function edit_post(Request $request, $id)
    {
        $request->validate([
            'post_title' => 'string|max:70',
            'post_content' => 'string|max:100000|nullable',
            'post_visibility' => 'string',
            'tags' => 'string|nullable'
        ]);
            
        $post = App\Post::find($id);

        if($post != null)
        {
            $post->post_title = $request->post_title;
            $post->post_content = $request->post_content;
            $post->category_id = $request->post_category;

            if($request->tags == "")
            { $post->tags =  null; }
            else
            { $post->tags = $request->tags; }

            //true is a string type, because ajax sends strings
            if($request->post_visibility == "true")
            { $post->visibility = 1; } 
            else 
            { $post->visibility = 0; }

            //true is a string type, because ajax sends strings
            if($request->post_pinned == "true")
            { $post->pinned = 1; } 
            else 
            { $post->pinned = 0; }

            //get list of files in the temp folder
            $temp_files = json_decode($request->file_list);
    
            //if temp is empty, this means no files were added, just save the post
            if(count($temp_files) == 0) 
            { $post->save(); }
            //if temp is not empty, replace all the files from the temp folder to another folder and then save the post
            else 
            {
                $folder_name = date("d-m-Y", strtotime($post->date))."-" . $post->post_title;
                //check if folder has been created
                $check = File::exists(storage_path("app/public/posts/" . $folder_name));
                
                if($check != true)
                { Storage::disk('public')->makeDirectory("posts/" . $folder_name); }

                foreach($temp_files as $file)
                {
                    //path for replacement
                    $new_path = storage_path("app/public/posts/") . $folder_name . "/" . $file;
                    
                    $move = File::move(storage_path("app/public/temp/" . $file), $new_path);
                
                    //if replacement failed, redirect back with error
                    if($move != true) 
                    { return abort(500, "Something went wrong while moving the files."); }
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
            
            return response()->json(['msg' => 'The changes have been saved to post "' . $request->post_title . '"']);
        }
        else
        { return abort(500, "Couldn't get the post from the database."); }
    }

    //upload files before post creation
    public function upload_files_to_temp_folder(Request $request)
    {  
       //get filename
       $filename = $request->filename;

       //uuid of a file
       $uuid8 = substr($request->dzuuid, 0, 7);

       //get pathinfo of a file
       $pathinfo = pathinfo($filename);

       $extension = $pathinfo['extension'];
       $name = $pathinfo['filename'];
       $filename = $name . "-" . $uuid8 . "." . $extension;
       
       //create an empty file and append chunks to it
       $file = fopen(storage_path('app/public/temp/') . $filename, "a");

       if($file != false)
       {
           //append chunk and save it
           $fwrite = fwrite($file, file_get_contents($request->file));

           if($fwrite != false)
           {
               fclose($file);

               return response()->json([
                'file_url' => asset("storage/temp/" . $filename),
                'filename' =>  $filename,
                'mime' => substr(File::mimeType(storage_path('app/public/temp/') . $filename),0,5)
               ]);  
           }
           else
           { return abort(500, "Couldn't append chunk to file '" . $filename . "'."); }
       }
       else
       { return abort(500, "Couldn't write file '".$filename."' into the 'temp' folder."); }
    }	    

    //clear temp folder
    public function clear_temp_folder()
    {
        $temp_files = File::files(storage_path("app/public/temp"));

        foreach($temp_files as $file)
        { unlink($file->getPathname()); }
    }

    //delete media file from post
    public function delete_file_from_post(Request $request)
    {
        //if id is not digit, this means the file was just added into the temp folder and it's not written into the database yet
        //the file should be removed from the temp folder
        if(ctype_digit($request->id) == false)
        {   
            $filename = $request->id; 
            
            //check if file exists
            if(is_file(storage_path("app/public/temp/" . $filename)))
            {   
                //delete file from temp
                $check = unlink(storage_path("app/public/temp/" . $filename));
                //if success, return response message
                if($check == true) 
                { return response()->json(['msg'=> $filename . " has been deleted from 'temp'."]); } 
                else
                { return abort(500, "Couldn't delete '" . $filename . "' from 'temp'."); }
            }
            else
            { return abort(500, "The file '" . $filename . "' doesn't exist in the 'temp' folder."); }
        }
        //if id IS digit, this means the file is written into the database
        //the file should be deleted both physically and from the database
        else
        {
            $media = App\Media::find($request->id);

            if($media != null)
            {   
                //delete the media file physically
                $check = unlink(storage_path("app/public/") . $media->media_url);

                if($check == true) 
                {
                    //get the post associated with the media file
                    $post = App\Post::find($media->post_id);

                    if($post != null)
                    {
                        $folder_name = date("d-m-Y",strtotime($post->date)) . "-" . $post->post_title;
                        $files = File::files(storage_path("app/public/posts/" . $folder_name));
    
                        //if the directory is empty, delete it
                        if(count($files) == 0)
                        { File::deleteDirectory(storage_path("app/public/posts/") . $folder_name); }
                        
                        $media->delete();
    
                        return response()->json(['result'=>'success']); 
                    }
                    else
                    { return abort(500, "Couldn't get the post associated with the media file from the database."); }
                }
                else 
                { return abort(500, "Couldn't get the media file from the database"); }
            }
            else
            { return abort(500, "Couldn't get the media file from the database."); }
        }
    }

    //VIEWS
    //view post
    public function show_post($id)
    {
        //get post by id
        $post = App\Post::find($id);
        //if post exists, view it
        if($post != null)
        {   
            //+1 to view counter
            // $post->view_count = $post->view_count + 1;
            $post->save();
    
            //get media files
            $media = App\Media::where('post_id', $id)->where('visibility','=', 1)->orderBy('media_type','asc')->orderBy('id','asc')->get();
            
            //if theres any media attached to this post, add subtitles to it
            if(count($media) > 0)
            {
                foreach($media as $m)
                {
                    if($m->media_type == "video")
                    {
                        $subs = App\Subtitles::where('media_id','=', $m->id)
                            ->where('visibility','=','1')
                            ->orderBy('display_name','asc')->get();
                        $m->subs = $subs;
                    } 
                }
            }
        
            //get newer post and older post
            $all_posts = App\Post::where('visibility','=','1')
                ->where('date','<=',Carbon::now()->format('Y-m-d'))
                ->orderBy('pinned','desc')
                ->orderBy('date', 'desc')
                ->orderBy('id','desc')->get();
            
            $current_index = 0;
            $previous_index = -1;
            $next_index = -1;

            //iterate thourgh all posts and find ids of the next and the previous posts
            foreach($all_posts as $p => $value)
            {
                if($value->id == $id)
                {   
                    if($current_index - 1 < 0 != true)
                    {
                        $next_index = $current_index - 1;
                        $post->next = $all_posts[$next_index]->id;
                    }
                    
                    if($current_index + 1 > count($all_posts)-1 != true)
                    {
                        $previous_index = $current_index + 1;
                        $post->previous = $all_posts[$previous_index]->id;
                    }

                    break;
                }

                $current_index++;
            }

            //check if user is admin
            $is_admin = Globals::check_admin();
            //get username
            $username = "";
            if(Auth::check())
            { $username = Auth::user()->name; }
            
            //get comments for Posts
            if($is_admin == true) //if user is admin, get all of the comments, if not, get only visible comments
            {
                $comments = App\Comment::where('post_id','=', $id)
                    ->where('reply_to','=',null)
                    ->orderBy('date','asc')
                    ->orderBy('id','asc')->get();
            }
            else
            {
                $comments = App\Comment::where('post_id','=', $id)
                    ->where('reply_to','=',null)
                    ->where('visibility','=',1)
                    ->orderBy('date','asc')
                    ->orderBy('id','asc')->get();
            }

            //recursive function that collects all the comments with replies and generates a comment tree
            function prepare_comments($array, $admin)
            {   
                foreach($array as $a)
                {   
                    if($a->is_logged_on != -1)
                    {   
                        $username = App\User::where('id','=',$a->is_logged_on)->get();
                        $a->username = $username[0]->name;
                    }
            
                    if($admin == true)
                    {
                        $replies = App\Comment::where('reply_to','=', $a->id)->get();
                        //get username for a reply
                        foreach($replies as $r)
                        {
                            if($r->reply_to != null)
                            { 
                                //
                                $r->reply_user = App\Comment::where('id','=',$r->reply_to)->get()[0]->username; 
                            }                        
                        }

                        $a->replies = $replies;
                    
                        prepare_comments($replies, $admin);
                    }
                    
                    else
                    {
                        $replies = App\Comment::where('reply_to','=', $a->id)->where('visibility','=',1)->get();
                        //get username for a reply
                        foreach($replies as $r)
                        {
                            if($r->reply_to != null)
                            { $r->reply_user = App\Comment::where('id','=',$r->reply_to)->get()[0]->username; }
                        }

                        $a->replies = $replies;
                    
                        prepare_comments($replies, $admin);
                    }
                } 
            }

            prepare_comments($comments, $is_admin);
  
            //count comment
            if($is_admin == true)
            { $post->comment_count = count(App\Comment::where('post_id','=', $id)->get()); }
            else
            { $post->comment_count = count(App\Comment::where('post_id','=', $id)->where('visibility','=',1)->get()); }

            //if theres more than one comment
            //commentS
            //or commenT
            if($post->comment_count > 1 || $post->comment_count == 0)
            { $post->comment_count .= " comments"; }  
            else
            { $post->comment_count .= " comment"; } 

            //gets tags for post
            $tags= explode(",", $post->tags);
            if(count($tags) == 1 && $tags[0] == "")
            { $post->tags = null; }
            else
            { $post->tags = $tags; }

            //get category of the post
            $post->category = App\Category::find($post->category_id)->category_name;
            //if it's 'blank', it won't be displayed
            if($post->category == "blank")
            { $post->category = ""; }
            
            //check post status, if visibility == 1, the post is visible to everyone
            if($post->visibility == 1)
            { return view('post', compact('post','username','comments','media','is_admin')); }
            //check if user is logged in
            else
            {  
                //if its Admin
                if(Globals::check_admin() == true)
                { return view('post', compact('post','username','media','comments','is_admin')); } 
                else //if logged in but not Admin, throw 404
                { return abort(403, "You must be logged in to view this page."); }
            } 
        }
        else //if post doesn't exist, throw 404
        { return abort(404, "Couldn't find the post."); }
    }

    //view all posts by tag
    public function show_posts_by_tag ($tag, Request $request)
    {
        $posts = App\Post::where('visibility','=','1')
            ->where('tags','like',"%" . $tag . "%")
            ->orderBy('date', 'desc')
            ->orderBy('id','desc')->paginate(7);

        $view_type = $request->cookie('view_type');

        if($view_type == null)
        { $view_type = App\Settings::all()[0]->view_type; }

        foreach($posts as $post)
        {
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;

            //attach category name to post
            $post->category = App\Category::find($post->category_id)->category_name;

            //count comments
            $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());

            if($post->comment_count > 1 || $post->comment_count == 0)
            { $post->comment_count .= " comments"; } 
            else 
            { $post->comment_count .= " comment"; }

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

    //change post's visibility
    public function change_post_visibility($id, $post_visibility)
    {   
        //get post id
        $post = App\Post::find($id);  
        
        if($post != null)
        {
            if($post->visibility != $post_visibility)
            {
                $post->visibility = $post_visibility;
                $post->save();
            }
            else
            { return redirect(url()->previous()); }

            return redirect(url()->previous());
        }
        else
        { return abort(503, "Couldn't get the post from the database."); }
    }

    //delete post
    public function delete_post(Request $request)
    {
        $post = App\Post::find($request->modal_form_input);

        if($post != null)
        {
            //delete all comments associated with this post
            $comments = App\Comment::where('post_id', $request->modal_form_input)->get();

            foreach($comments as $comment)
            { $comment->delete(); }

            //delete all files associated with this post
            $media = App\Media::where('post_id', $request->modal_form_input)->get();

            foreach($media as $m)
            {
                if($m->media_type == "video")
                {
                    $subtitles = App\Subtitles::where('media_id', $m->id)->get();

                    foreach($subtitles as $s)
                    { $s->delete(); }
                }
                
                $pos = strripos($m->media_url,"/");
                $path = substr($m->media_url, 0, $pos);
                File::deleteDirectory(storage_path('app/public/'.$path));
                $m->delete();
            }

            $post->delete();
            if($request->edit_post_delete === "true")
            { return redirect()->to('/control/posts'); }
            return redirect(url()->previous());
        }
    }

    //submit comment
    public function submit_comment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:25',
            'comment_content' => 'required|max:1000'
        ]);

        if($validator->fails())
        { return redirect(url()->previous() . "#comment_form")->withErrors($validator)->withInput(); }

        $comment = new App\Comment;

        $comment->comment_content = $request->comment_content;
        $comment->post_id = $id;
        $comment->date = Carbon::now();
        $comment->reply_to = $request->reply_to;

        if(Auth::check())
        {
            $comment->is_logged_on = Auth::user()->id;    
            $comment->username = Auth::user()->name;        
        }
        else
        {
            $comment->is_logged_on = -1;
            $comment->username = $request->username;
        }

        $comment->save();

        //the id of the comment that was submitted
        $latest_comment_id = App\Comment::orderBy('id','desc')->first()->id;

        //return to the post page with the anchor for the submitted comment
        return redirect(url()->previous()."#comment_anchor_" . $latest_comment_id);
    }

    //show/hide/delete comment (for Admin)
    public function change_comment_status(Request $request)
    {
        $comment = App\Comment::find($request->comment_id);

        if($comment != null)
        {
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
                $comment->deleted = 1;
                $comment->save();
            }
            else if($request->action == "restore")
            {
                $comment->deleted = 0;
                $comment->save();
            }
            else if($request->action == "purge")
            {
                $comment->delete();
            }
            else
            { return redirect(url()->previous()); }
            
            return redirect(url()->previous());
        }
        else
        { return abort(500, "Couldn't get the comment from the database."); }
    }

    //pin/unpin post
    public function pin_post(Request $request)
    {
        $post = App\Post::find($request->id);

        if($post != null)
        {
            if($post->pinned == 0)
            { $post->pinned = 1; }
            else
            { $post->pinned = 0; }
    
            $post->save();
    
            return redirect()->back();
        }
        else
        { return abort(500, "Couldn't get the post from the database."); }
    }
} 

