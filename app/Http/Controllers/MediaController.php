<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Storage;

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
        $media->date = date('d.m.Y',strtotime($media->created_at));
        $media->size = round(Storage::size('/public/'.$media->media_url) / 1000000, 1) . " Mb";

        return view('/control_panel/media/view_media', compact('media'));
    }
}
