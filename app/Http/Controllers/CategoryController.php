<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;

class CategoryController extends Controller
{
    //КОНТРОЛЬНАЯ ПАНЕЛЬ
    //вывод списка категорий в панели управления
    public function category_list ()
    {
        $categs = App\Category::where('category_name','!=','blank')->get();
        return view('control_panel/categories/categories', compact('categs'));    
    }

    //показать страницу создания категории
    public function show_create_category()
    {
        return view('control_panel/categories/add_category');
    }

    //создать категорию
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

    //показать страницу редактирования категории
    public function show_edit_category($id)
    {
        $categ = App\Category::find($id);
        return view('control_panel/categories/edit_category', compact('categ'));
    }
    
    //сохранить изменения в категории
    public function edit_category(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'string|max:20',
        ]);

        $categ = App\Category::find($id);
        $categ->category_name = $request->category_name;
        $categ->save();
        return redirect(url('/control/categories'));
    }

    //удалить категорию
    public function delete_category(Request $request)
    {   
        //получаем id пустой категории (чтобы прописать этот id у постов, т.к категория удаляется)
        $blank_id = App\Category::where('category_name','=', 'blank')->first()->id;

        //получаем посты удаляемой категории
        $posts = App\Post::where('category_id','=', $request->modal_form_input)->get();
        
        //и прописываем у них пустую категорию
        foreach($posts as $post){
            $p = App\Post::find($post->id);
            $p->category_id = $blank_id;
            $p->save();
        }

        //удаляем категорию
        $categ = App\Category::find($request->modal_form_input);
        $categ->delete();

        return redirect(url('/control/categories'));
    }

    //ВЫВОД
    //показать посты по категориям
    public function show_posts_by_category($category_name)
    {
        //получаем категорию по названию категории
        $categ = App\Category::where('category_name','=',$category_name)->first();
        
        //получаем посты в этой категории
        $posts = App\Post::where('category_id','=',$categ->id)->where('visibility','=','1')->where('date','<=',Carbon::now()->format('Y-m-d'))->orderBy('date','desc')->orderBy('id','desc')->paginate(15);

        foreach($posts as $post)
        {
          //получаем список тегов поста
          $tags = explode(",", $post->tags);
          //если в поле тегов был записан один пустой символ, то делаем теги null
          if(count($tags) == 1 && $tags[0] == "")
          {$post->tags = null;}
          else 
          {$post->tags = $tags;}

          //считаем кол-во комментов под постом
          $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());
          //если комментов больше одного, то под постом будет написано commentS, а не comment
          if($post->comment_count > 1 || $post->comment_count == 0)
          {$post->comment_count .= " comments";} 
          else 
          {$post->comment_count .= " comment";}

          //получаем список файлов у поста
          $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();
          //если файлы есть, то
          if(count($media) != 0)
          {   
                foreach($media as $m)
                {
                    $subs = App\Subtitles::where('media_id','=',$m->id)->where('visibility','=','1')->get();
                    $m->subs = $subs;
                }
                
              //прикрепляем медиа к посту
              $post->media = $media;
              //прикрепить тип медиа к медиа файлу
              $post->media_type = $media[0]->media_type;
          }
        }
        
        return view('category_view', compact('categ', 'posts'));
    }

}
