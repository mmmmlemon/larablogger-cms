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

    //view Media Browser page
    public function media_list()
    {
        //get all the media from database
        $media = App\Media::orderBy('post_id','desc')->paginate(15);

        //add additional info to each media file
        foreach($media as $m)
        {   
            $m->filename = basename($m->media_url); //filename
          
            $post_title = App\Post::find($m->post_id)->post_title;
            $m->post_title = $post_title; //title of the Post that is related to the media file
        }
        
        return view('/control_panel/media/media', compact('media'));
    }

    //view 'Edit media' page
    public function view_media($id)
    {
        $media = App\Media::find($id); //media file
        $post_title = App\Post::find($media->post_id)->post_title;
        $media->post_title = $post_title;  //Post title
        $media->date = date('d.m.Y',strtotime($media->created_at)); //media - date of upload
        $media->size = round(Storage::size('/public/'.$media->media_url) / 1000000, 1) . " Mb"; //media - size

        //subtitles
        $subs = App\Subtitles::where('media_id','=',$media->id)->orderBy("display_name","asc")->get(); //get all of the subtitles
        $subs_for_video = App\Subtitles::where('media_id','=',$media->id)->where('visibility','=',1)->orderBy("display_name","asc")->get(); //get all of the subtitles (for Preview video player)

        return view('/control_panel/media/view_media', compact('media','subs','subs_for_video'));
    }

    //save changes in Media file
    public function edit_media(Request $request, $id)
    { 
        //get media file from the database
        $media = App\Media::find($id);
        $media->display_name = $request->display_name;

        if($request->visibility == "on")
        {$media->visibility = 1;}
        else
        {$media->visibility = 0;}

        //get the position of the last slash character in path to use it later
        $pos = strrpos($media->media_url, "/");

        //if a thumbnail was added
        if($request->thumbnail != null)
        {   
            //if media file already has a a thumbnail
            if($media->thumbnail_url != null)
            {   
                unlink(storage_path('app/public/'.$media->thumbnail_url)); //physically remove the old thumbnail
                $media->thumbnail_url = null; //remove the url from the database
            }

            $path = substr($media->media_url, 0, $pos) . "/thumbnail"; //new path to the new thumbnail

            //check if such path already exists
            $check = File::exists(storage_path("app/public/".$path));

            //if it doesn't exist
            if($check == false) 
            {
                $folder_created = Storage::disk('public')->makeDirectory($path); //create new folder

                if($folder_created){
                    Storage::disk('public')->put("/storage", $request->thumbnail); //save the new thumbnail
                }
            }

            //generate file name 
            $filename = "thumbnail_".rand(0,99).".".$request->file('thumbnail')->getClientOriginalExtension();
            //create image
            $img = Image::make($request->thumbnail);
            $img->fit(640,360); //fir image into 640x360
            $img->save(storage_path('app/public/').$path."/".$filename); //save image
            $media->thumbnail_url = $path."/".$filename; //write url into the database
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
                $folder_created = Storage::disk('public')->makeDirectory($path); //create new folder
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
    
        $media->save();

        return redirect()->back();
    }

    //delete thumbnail from
    public function remove_thumbnail($id)
    {
        $media = App\Media::find($id);

        unlink(storage_path('app/public/'.$media->thumbnail_url));
        $media->thumbnail_url = null;
        $media->save();

        return redirect()->back();
    }

    //make subtitles visible/hidden
    public function change_subs_status(Request $request)
    {
        $sub = App\Subtitles::find($request->sub_id);
        $sub->visibility = $request->visibility;
        $sub->save();
        return response()->json(['msg'=>'success']);
    }

    //change display name for a subtitle file
    public function change_subs_display_name(Request $request)
    {
       $sub = App\Subtitles::find($request->sub_id);
       $sub->display_name = $request->display_name;
       $sub->save();

       return response()->json(['msg'=>'success']);
    }

    //delete a subtitle file
    public function delete_subs(Request $request)
    {
        $sub = App\Subtitles::find($request->sub_id)    ;
        unlink(storage_path('app/public/'.$sub->sub_url));
        $sub->delete();
        return response()->json(['msg'=>'success']);
    }

    //delete a media file
    public function delete_media(Request $request)
    {
        $media = App\Media::find($request->id);
        unlink(storage_path('app/public/'.$media->media_url));

        if($media->media_type == "video")
        {
            $subs = App\Subtitles::where('media_id', $media->id)->get();

            foreach($subs as $s)
            {
                $s->delete();
            }
        
        }

        $media->delete();
        return redirect()->back();
    }

    //show page for file uploads
    public function show_upload_file(){
        
        return view('/control_panel/media/upload_file');
    }

    
    //SAVE MANUALLY UPLOADED MEDIA FILES
    public function save_uploaded_media_files(Request $request){

        //get list of files in the temp folder
        $temp_files = json_decode($request->file_list);
        $folder_name = "uploaded_manually/".date("M Y");
        //check if folder has been created
        $check = File::exists(storage_path("app/public/".$folder_name));

        if($check != true)
        {
            Storage::disk('public')->makeDirectory($folder_name);
        }

        foreach($temp_files as $file)
        {
           
            //path for replacement
            $new_path = storage_path("app/public/").$folder_name."/".$file->actual_filename;

            $move = File::move(storage_path("app/public/temp/".$file->actual_filename), $new_path);
        
            //if replacement failed, redirect back with error
            if($move != true) 
            {return Redirect::back()->withErrors(['err', 'Something went wrong while moving the files.']);}
            else
            {   
                $media = new App\Media; //write media into the database
                $media->post_id = null;
                $media->media_url = $folder_name."/".$file->actual_filename;
                $mime = substr(File::mimeType($new_path), 0, 5); //get mime type of media
                $media->media_type = $mime;
                $media->display_name = $file->display_filename;
                $media->actual_name = $file->actual_filename;
                $media->visibility = 1;
                $media->save(); 
            }
        }   

    }

}
