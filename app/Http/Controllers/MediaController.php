<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Storage;
use File;
use Image;

//functions related to Media Browser and media files manipulations
class MediaController extends Controller
{

    //view 'Media Browser' page
    public function view_media_browser()
    {
        //get all the media from database
        $media = App\Media::orderBy('id','desc')->orderBy('post_id','desc')->paginate(15);

        if($media != null)
        {
           //add additional info to each media file
           foreach($media as $m)
           {   
               //filename
               $m->filename = basename($m->media_url); 
               //post title by default
               $post_title = "—"; 
               //if media is attached to a post, set new post title
               if($m->post_id != null)
               { 
                   $post_title = App\Post::find($m->post_id)->post_title; 
               } 
               //title of the Post that is related to the media file
               $m->post_title = $post_title; 
           }

           return view('/control_panel/media/media', compact('media'));
        }
        else
        {
            return abort(500, "Can't access the media files from the database.");
        }
    }

    //view 'Media' page
    public function view_media($id)
    {
        //media file
        $media = App\Media::find($id);
        if($media != null)
        {
           //post title by default 
           $media->post_title = "—";
           //if media is attached to a post, set post title
           if($media->post_id != null)
           {
               $media->post_title = App\Post::find($media->post_id)->post_title;
           }
           //media - date of upload
           $media->date = date('d.m.Y',strtotime($media->created_at));
           //media - size, rounded
           $media->size = round(Storage::size('/public/'.$media->media_url) / 1000000, 1) . " Mb"; 

           //subtitles, get all of the subtitles
           $subtitles = App\Subtitles::where('media_id','=',$media->id)->orderBy("display_name","asc")->get(); 
           //get all of the subtitles (for Preview video player)
           $subtitles_for_video = App\Subtitles::where('media_id','=',$media->id)->where('visibility','=',1)->orderBy("display_name","asc")->get(); 

           return view('/control_panel/media/view_media', compact('media','subtitles','subtitles_for_video'));
        }
        else
        {
            return abort(500, "Can't access the media file from the database.");
        }
    }

    //save changes in Media file
    public function edit_media(Request $request, $id)
    { 
        //get media file from the database
        $media = App\Media::find($id);
        if($media != null)
        {
            //save display name
            $media->display_name = $request->display_name;

            //attached post
            if($request->edit_post_id != null)
            { $media->post_id = $request->edit_post_id; }
            else
            { $media->post_id = null; }

            //save visibility options
            if($request->visibility == "on")
            { $media->visibility = 1; }
            else
            { $media->visibility = 0; }

            //get the position of the last slash character in path to use it later
            $pos = strrpos($media->media_url, "/");

            //if a thumbnail was added
            if($request->thumbnail != null)
            {   
                //if media file already has a a thumbnail
                if($media->thumbnail_url != null)
                {   
                    //physically remove the old thumbnail
                    unlink(storage_path('app/public/'.$media->thumbnail_url)); 
                    //remove the url from the database
                    $media->thumbnail_url = null; 
                }

                //new path to the new thumbnail
                $path = substr($media->media_url, 0, $pos) . "/thumbnail"; 

                //check if such path already exists
                $check = File::exists(storage_path("app/public/".$path));

                //if it doesn't exist
                if($check == false) 
                {
                    //create new folder
                    $folder_created = Storage::disk('public')->makeDirectory($path); 

                    if($folder_created)
                    {
                        //save the new thumbnail
                        Storage::disk('public')->put("/storage", $request->thumbnail); 
                    }
                }

                //generate file name 
                $filename = "thumbnail_".rand(0,99).".".$request->file('thumbnail')->getClientOriginalExtension();
                //create image
                $img = Image::make($request->thumbnail);
                //fit image into 640x360
                $img->fit(640,360); 
                //save image
                $img->save(storage_path('app/public/').$path."/".$filename); 
                //write url into the database
                $media->thumbnail_url = $path."/".$filename; 
            }

            //if a subtitle file was added
            if($request->subtitles != null)
            {
                //path to subtitles folder
                $path = substr($media->media_url, 0, $pos) . "/subtitles";

                //check if the folder already exists
                $check = File::exists(storage_path("app/public/".$path));

                //if it doesn't exist
                if($check == false)
                {
                    //create new folder
                    $folder_created = Storage::disk('public')->makeDirectory($path); 
                }

                //get the subtitle file(s)
                $files = $request->file('subtitles');

                foreach($request->file('subtitles') as $subtitle)
                {   
                    //save new subtitle file
                    $check = Storage::disk('public')->put($path."/".$subtitle->getClientOriginalName(), file_get_contents($subtitle));

                    //if saved, write it into the database
                    if($check)
                    {
                        $sub = new App\Subtitles;
                        $sub->media_id = $media->id;
                        $sub->sub_url = $path."/".$subtitle->getClientOriginalName();
                        $sub->display_name = $subtitle->getClientOriginalName();
                        $sub->actual_name = $subtitle->getClientOriginalName();
                        $sub->visibility = 1;     
                        $sub->save();
                    }
                }
            }

            //save changes to the media file
            $media->save();
            return redirect()->back();
        }
        else
        {
            return abort(500, "Can't access the media file from the database");
        }
    }

    //delete thumbnail from the media file
    public function remove_thumbnail_from_media($id)
    {   
        //get the media file
        $media = App\Media::find($id);
        if($media != null)
        {
           //remove the thumbnail file physically
           unlink(storage_path('app/public/'.$media->thumbnail_url));
           //remove the thumbnail url from the DB
           $media->thumbnail_url = null;
           $media->save();
           return redirect()->back();
        }
        else
        {
            return abort(500, "Can't acees the media file from the database");
        }
    }

    //make subtitles visible/hidden
    public function change_subtitles_visibility(Request $request)
    {
        $subtitles = App\Subtitles::find($request->sub_id);
        if($subtitles != null)
        {
           $subtitles->visibility = $request->visibility;
           $subtitles->save();
           return true;
        }
        else
        {
            return abort(500, "Can't acess the subtitle file from the database");
        }
    }

    //change display name for the subtitle file
    public function change_subtitles_display_name(Request $request)
    {
       $subtitles = App\Subtitles::find($request->sub_id);
       if($subtitles != null)
       {
           $subtitles->display_name = $request->display_name;
           $subtitles->save();
           return true;
       }
       else
       {
        return abort(500, "Can't access the subtitle file from the database.");
       } 
    }

    //delete the subtitle file
    public function delete_subtitles(Request $request)
    {
        $subtitles = App\Subtitles::find($request->sub_id);
        if($subtitles != null)
        {
           //delete the subtitle file physically
           unlink(storage_path('app/public/'.$sub->sub_url));
           //delete the subtitle file from the DB
           $subtitles->delete();
           return true;
        }
        else
        {
            return abort(500, "Can't access the subtitle file from the database.");
        }
    }

    //delete a media file
    public function delete_media(Request $request)
    {
        //get media file
        $media = App\Media::find($request->id);
        if($media != null)
        {
            //delete media file's entire folder physically
            unlink(storage_path('app/public/'.$media->media_url));
            //if media file is a video
            if($media->media_type == "video")
            {   
                //get all of its subtitles
                $subtitles = App\Subtitles::where('media_id', $media->id)->get();

                //delete all subtitles from the DB
                foreach($subtitles as $s)
                {
                    $s->delete();
                }
            }
            //delete media file from the DB
            $media->delete();
            return redirect()->back();
        }
        else
        {
            return abort(500, "Can't access the media file from the database.");
        }
    }

    //show page for file uploads
    public function view_upload_file_page()
    {  
        return view('/control_panel/media/upload_file');
    }

    //save manually uploaded media files
    public function save_uploaded_media_files(Request $request)
    {
        //get list of all uploaded files
        $temp_files = json_decode($request->file_list);

        //get folder name for file replacement
        $folder_name = "uploaded_manually/".date("M Y");
        //check if folder has been created already
        $check = File::exists(storage_path("app/public/".$folder_name));
        //if folder doesn't exist, create it
        if($check != true)
        {
            Storage::disk('public')->makeDirectory($folder_name);
        }

        //for each uploaded file, do this
        foreach($temp_files as $file)
        {
            //path for replacement
            $new_path = storage_path("app/public/").$folder_name."/".$file->actual_filename;
            //move files to the new folder
            $move = File::move(storage_path("app/public/temp/".$file->actual_filename), $new_path);
        
            //if replacement failed, redirect back with error
            if($move != true) 
            {
                return abort(500, "Couldn't move the media files from the temp folder to '" . $new_path ."'.");
            }
            else
            {   
                //write media into the database
                $media = new App\Media; 
                //if file is not attached to a post, set post_id as null
                if($file->post_id == null)
                { $media->post_id = null; }
                else
                { $media->post_id = $file->post_id; }
                $media->media_url = $folder_name."/".$file->actual_filename;
                //get mime type of media
                $mime = substr(File::mimeType($new_path), 0, 5);
                $media->media_type = $mime;
                $media->display_name = $file->display_filename;
                $media->actual_name = $file->actual_filename;
                $media->visibility = 1;
                $media->save(); 
            }
        }   
    }

    //find post(s)
    public function find_post(Request $request)
    {
        //get all posts that fit the search query
        $posts = App\Post::select('post_title','date','id','category_id')->where('post_title','like', '%'.$request->search_value.'%')->get();

        if($posts != null)
        {
            foreach($posts as $post)
            {
                //add date to post
                $post->date = date('d.m.Y', strtotime($post->date));
                //add category to post
                $post->category = App\Category::where('id','=', $post->category_id)->get()->first()->category_name;
            }
      
            return json_encode($posts);
        }
        else
        {
            return false;
        }

    }
    
    //increment view count for the video
    public function increment_view_count_for_video(Request $request)
    {   
        //get media file
        $media = App\Media::find($request->media_id);
        //if media file exists in DB, increment its view_counter
        if($media != null && $media->media_type == "video")
        {
            $media->view_count = $media->view_count + 1;
            $media->save();
            return true;
        }
        else
        {
            return abort(500, "Couldn't access the media file from the database.");
        }  
    }
}
