<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Storage;
use File;
use Image;

class MediaController extends Controller
{

    //страница со списком всех медиа файлов
    public function index(){
        
        //получаем все медиафайлы
        $media = App\Media::orderBy('post_id','desc')->paginate(15);

        //добавляем к ним дополнительную информацию
        foreach($media as $m)
        {   
            $m->filename = basename($m->media_url);
            //получаем наименование поста, к которому относится файл
            $post_title = App\Post::find($m->post_id)->post_title;
            $m->post_title = $post_title;
        }
        
        return view('/control_panel/media/media', compact('media'));
    }

    //показать страницу с информацией о медиа файле \ редактор
    public function view_media($id){
       
        $media = App\Media::find($id);
        $post_title = App\Post::find($media->post_id)->post_title;
        $media->post_title = $post_title;
        $media->date = date('d.m.Y',strtotime($media->created_at));
        $media->size = round(Storage::size('/public/'.$media->media_url) / 1000000, 1) . " Mb";

        $subs = App\Subtitles::where('media_id','=',$media->id)->get();

        return view('/control_panel/media/view_media', compact('media','subs'));
    }

    //сохранить изменения в медиа
    public function edit_media(Request $request, $id){
        
        //получаем запись о файле из БД
        $media = App\Media::find($id);
        $media->display_name = $request->display_name;

        if($request->visibility == "on")
        {$media->visibility = 1;}
        else
        {$media->visibility = 0;}

        //получаем путь до папки с превьюхой
        $pos = strrpos($media->media_url, "/");
    

        //если была добавлена картинка thumbnail
        if($request->thumbnail != null)
        {
            $path = substr($media->media_url, 0, $pos) . "/thumbnail";
            //проверяем существует ли уже такая папка
            $check = File::exists(storage_path("app\\public\\".$path));
            if ($check == false) {
                $folder_created = Storage::disk('public')->makeDirectory($path);
                if($folder_created){
                    Storage::disk('public')->put("/storage", $request->thumbnail);
                }
            }
               $filename = "thumbnail_".$media->id.".".$request->file('thumbnail')->getClientOriginalExtension();
               $img = Image::make($request->thumbnail);
               $img->fit(640,360);
               $img->save(storage_path('\\app\\public\\').$path."/".$filename);
               $media->thumbnail_url = $path."/".$filename;
        }

        //если добавлен файл субтитров
        if($request->subtitles != null)
        {
            $path = substr($media->media_url, 0, $pos) . "/subtitles";

            $check = File::exists(storage_path("app\\public\\".$path));
            if ($check == false) {
                $folder_created = Storage::disk('public')->makeDirectory($path);
            }
            $files = $request->file('subtitles');

            foreach($request->file('subtitles') as $subtitle)
            {
                $check = Storage::disk('public')->put($path."/".$subtitle->getClientOriginalName(), file_get_contents($subtitle));
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

    //удалить thumbnail
    public function remove_thumbnail($id){
        $media = App\Media::find($id);

        unlink(storage_path('app\\public\\'.$media->thumbnail_url));
        $media->thumbnail_url = null;
        $media->save();

        return redirect()->back();
    }

    //показать\спрятать субтитры
    public function change_subs_status(Request $request){
        $sub = App\Subtitles::find($request->sub_id);
        $sub->visibility = $request->visibility;
        $sub->save();
        return response()->json(['msg'=>'success']);
    }

    public function delete_subs(Request $request){
        $sub = App\Subtitles::find($request->sub_id);
        unlink(storage_path('app\\public\\'.$sub->sub_url));
        $sub->delete();
        return response()->json(['msg'=>'success']);
    }
}
