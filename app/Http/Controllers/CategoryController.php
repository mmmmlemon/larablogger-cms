<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CategoryController extends Controller
{
    public function index($category_name)
    {
        $categ = App\Category::where('category_name','=',$category_name)->first();

        $posts = App\Post::where('category_id','=',$categ->id)->where('visibility','=','1')->orderBy('date','desc')->get();

        return view('category_view', compact('categ', 'posts'));
    }
}
