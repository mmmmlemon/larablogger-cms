<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Storage;
use File;
use Validator;
use Image;

class MediaController extends Controller
{

    //страница со списком всех медиа файлов
    public function media_list()
    {
        //получаем все медиафайлы
        $media = App\Media::orderBy('post_id','desc')->paginate(15);

        //добавляем к ним дополнительную информацию
        foreach($media as $m)
        {   //имя файла
            $m->filename = basename($m->media_url);
            //получаем наименование поста, к которому относится файл
            $post_title = App\Post::find($m->post_id)->post_title;
            $m->post_title = $post_title;
        }

        return view('/control_panel/media/media', compact('media'));
    }

    //показать страницу с информацией о медиа файле \ редактор
    public function view_media($id)
    {
        $media = App\Media::find($id); //медиа файл
        $post_title = App\Post::find($media->post_id)->post_title; //наименование поста
        $media->post_title = $post_title;
        $media->date = date('d.m.Y',strtotime($media->created_at)); //дата создания
        $media->size = round(Storage::size('/public/'.$media->media_url) / 1000000, 1) . " Mb"; //размер в Мб
        $subs = App\Subtitles::where('media_id','=',$media->id)->get(); //субтитры относящиеся к файлу

        return view('/control_panel/media/view_media', compact('media','subs'));
    }

    //сохранить изменения в медиа
    public function edit_media(Request $request, $id)
    {
        //получаем запись о файле из БД
        $media = App\Media::find($id);


        $validator = Validator::make($request->all(),[
            'display_name' => 'required|string|max:60',
            'thumbnail' => 'mimes:jpeg,jpg,png|max:3000',
            'subtitles' => 'mimes:srt|max:1000'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //имя, которое будет отображаться для файла
        $media->display_name = $request->display_name;

        //вкл\выкл видимость файла 
        if($request->visibility == "on")
        {$media->visibility = 1;}
        else
        {$media->visibility = 0;}

        $pos = strrpos($media->media_url, "/");

        //если была добавлена картинка thumbnail
        if($request->thumbnail != null)
        {
            //получаем путь до папки с thumbnail из параметра media_url медиа файла
            $path = substr($media->media_url, 0, $pos) . "/thumbnail";
            //проверяем существует ли уже такая папка
            $check = File::exists(storage_path("app\\public\\".$path));
            if ($check == false) 
            {   //если папки нет, то создаем её
                $folder_created = Storage::disk('public')->makeDirectory($path);
                if($folder_created)
                {   
                    Storage::disk('public')->put("/storage", $request->thumbnail);
                }
                else
                {   
                    redirect()->back();
                }
            }
            //генерируем новое имя файла
            $filename = "thumbnail_".$media->id.".".$request->file('thumbnail')->getClientOriginalExtension();
            //создаем изображение и меняем его размер
            $img = Image::make($request->thumbnail);
            $img->fit(640,360);
            //сохраняем изображение и запись о нем в БД
            $img->save(storage_path('\\app\\public\\').$path."/".$filename);
            $media->thumbnail_url = $path."/".$filename;
        }

        //если добавлен файл субтитров
        if($request->subtitles != null)
        {   
            //получаем путь до папки с субтитрами
            $path = substr($media->media_url, 0, $pos) . "/subtitles";
            //проверяем существует ли эта папка
            $check = File::exists(storage_path("app\\public\\".$path));

            //если не существует, то создаем её
            if ($check == false) 
            {
                $folder_created = Storage::disk('public')->makeDirectory($path);
            }

            //получаем все файлы субтитров добавленные в форму
            $files = $request->file('subtitles');
            
            foreach($request->file('subtitles') as $subtitle)
            {
                //сохраняем файл субтитров в папку
                $check = Storage::disk('public')->put($path."/".$subtitle->getClientOriginalName(), file_get_contents($subtitle));
                if($check) //если сохранение удалось
                {   //создаем запись о субтитрах в БД
                    $sub = new App\Subtitles;
                    $sub->media_id = $media->id;
                    $sub->sub_url = $path."/".$subtitle->getClientOriginalName();
                    $sub->display_name = $subtitle->getClientOriginalName();
                    $sub->actual_name = $subtitle->getClientOriginalName();
                    $sub->visibility = 1;     
                    $sub->save();
                }
                else
                {
                    redirect()->back();
                }
            }
        }
    
        $media->save();

        return redirect()->back();
    }

    //удалить thumbnail
    public function remove_thumbnail($id)
    {
        $media = App\Media::find($id);

        unlink(storage_path('app\\public\\'.$media->thumbnail_url));
        $media->thumbnail_url = null;
        $media->save();

        return redirect()->back();
    }

    //показать\спрятать субтитры
    public function change_subs_status(Request $request)
    {
        $sub = App\Subtitles::find($request->sub_id);
        $sub->visibility = $request->visibility;
        $sub->save();
        return response()->json(['msg'=>'success']);
    }

    //удалить субтитры
    public function delete_subs(Request $request)
    {
        $sub = App\Subtitles::find($request->sub_id);
        unlink(storage_path('app\\public\\'.$sub->sub_url));
        $sub->delete();
        return response()->json(['msg'=>'success']);
    }

    //поменять имя субтитров
    public function change_subs_display_name(Request $request)
    {
       $sub = App\Subtitles::find($request->sub_id);
       $sub->display_name = $request->display_name;
       $sub->save();

       return response()->json(['msg'=>'success']);
    }

    //удалить медиа файл
    public function delete_media(Request $request)
    {
       $subs = App\Subtitles::where('media_id','=',$request->id)->get();
       foreach($subs as $s)
       {
           $s->delete();
       }
       $media = App\Media::find($request->id);
       $media->delete();
       return redirect()->back();
    }
}
