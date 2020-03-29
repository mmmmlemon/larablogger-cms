<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CategoryController extends Controller
{
    public function show_posts_by_category($category_name)
    {
        $categ = App\Category::where('category_name','=',$category_name)->first();

        $posts = App\Post::where('category_id','=',$categ->id)->where('visibility','=','1')->orderBy('date','desc')->paginate(15);

        foreach($posts as $post){
            $tags_separate = explode(",", $post->tags);
            $post->tags = $tags_separate;
        }


        return view('category_view', compact('categ', 'posts'));
    }

    public function index()
    {
        $categs = App\Category::where('category_name','!=','blank')->get();
        return view('control_panel/categories/categories', compact('categs'));    
    }

    public function create_category(Request $request)
    {
        $request->validate([
            'category_name' => 'string|max:20',
        ]);

        $categ = new App\Category;
        $categ->category_name = $request->category_name;
        $categ->save();

       
        return redirect(url('/control/categories'));
    }

    public function edit_category($id){
        $categ = App\Category::find($id);

        return view('control_panel/categories/edit_category', compact('categ'));
    }

    public function save_category(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'string|max:20',
        ]);

        $categ = App\Category::find($id);
        $categ->category_name = $request->category_name;
        $categ->save();
        return redirect(url('/control/categories'));
    }

    public function delete_category($id)
    {   
        $blank_id = App\Category::where('category_name','=', 'blank')->first()->id;

        $posts = App\Post::where('category_id','=', $id)->get();
        foreach($posts as $post){
            $p = App\Post::find($post->id);
            $p->category_id = 0;
            $p->save();
        }
        
        $categ = App\Category::find($id);
        $categ->delete();

        return redirect(url('/control/categories'));
    }
}
