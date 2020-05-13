<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Storage;
use File;

class MediaController extends Controller
{

    //страница со списком всех медиа файлов
    public function index(){
        
        //получаем все медиафайлы
        $media = App\Media::orderBy('post_id','desc')->get();

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

        return view('/control_panel/media/view_media', compact('media'));
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

        //если была добавлена картинка thumbnail
        if($request->thumbnail != null)
        {
            //получаем путь до папки с превьюхой
            $pos = strrpos($media->media_url, "/");
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
               $request->file('thumbnail')->storeAs("/public/".$path, $filename);
               $media->thumbnail_url = $path."/".$filename;
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
}
