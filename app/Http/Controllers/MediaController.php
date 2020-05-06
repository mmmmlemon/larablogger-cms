<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class MediaController extends Controller
{

    //страница со списком всех медиа файлов
    public function index(){
        
        $media = App\Media::orderBy('id','desc')->get();

        return view('/control_panel/media/media', compact('media'));

    }
}
