<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

//functions related to Categories

class CategoryController extends Controller
{
    //CONTROL PANEL
    //show Category list in control panel
    public function view_categories_page()
    {
        $categories = App\Category::where('category_name','!=','blank')->orderBy('visual_order','asc')->get();

        if(count($categories) != null)
        {   
            //get max of visual_order property
            $max = App\Category::max('visual_order');
            return view('control_panel/categories/categories', compact('categories','max'));
        }
        else
        { return abort(500, "Couldn't get categories from the database."); }
    }

    //show page 'Create category'
    public function view_create_category_page()
    {
        return view('control_panel/categories/add_category');
    }

    //create and save a category
    public function create_category(Request $request)
    {
        $request->validate([
            'category_name' => 'string|max:50',
        ]);

        $category = new App\Category;
        $category->category_name = $request->category_name;

        //get max value of visual order
        $max = App\Category::max('visual_order');

        //increment it, so that the created category appears on the top of the list
        $category->visual_order = $max + 1;
        $category->save();

        return redirect(url('/control/categories'));
    }

    //show page 'Edit category'
    public function view_edit_category_page($id)
    {
        $category = App\Category::find($id);
        if($category != null)
        { return view('control_panel/categories/edit_category', compact('categ')); }
        else
        { return abort(500, "Couldn't get the category from the database."); }
        
    }
    
    //save changes in a Category
    public function edit_category(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'string|max:50',
        ]);

        $category = App\Category::find($id);

        if($category != null)
        {
            $category->category_name = $request->category_name;
            $category->save();
    
            return redirect(url('/control/categories'));  
        }
        else
        { return abort(500, "Couldn't get the category from the database."); }
    }

    //delete a Category from the database
    public function delete_category(Request $request)
    {   
        //gets id of the 'blank' Category (to write this id in those posts that were added to the Category that is getting deleted)
        $blank_id = App\Category::where('category_name','=', 'blank')->first()->id;

        //get posts of a Category that is getting deleted
        $posts = App\Post::where('category_id','=', $request->modal_form_input)->get();
        
        //write the id of a 'blank' category to those posts
        foreach($posts as $post)
        {
            $p = App\Post::find($post->id);
            $p->category_id = $blank_id;
            $p->save();
        }

        //deleting the Category from the database
        $category = App\Category::find($request->modal_form_input);
        $category->delete();

        return redirect(url('/control/categories'));
    }

    //POSTS DISPLAY
    //show Posts by a Category
    public function show_posts_by_category($category_name, Request $request)
    {
        $view_type = $request->cookie('view_type');

        $paginate = 9;

        if($view_type == null)
        { $view_type = config('settings')->view_type; }
        if($view_type == 'grid')
        { $paginate = 27; }

        $isMobile = config('isMobile');

        //get the Category by the name (from route url)
        $category = App\Category::where('category_name', '=', $category_name)->first();

        //get all the posts in this Category by its id
        $posts = App\Post::where('category_id', '=', $category->id)
            ->where('visibility','=','1')
            ->where('date','<=',Carbon::now()->format('Y-m-d'))
            ->orderBy('pinned','desc')
            ->orderBy('date','desc')
            ->orderBy('id','desc')->paginate($paginate);

        //for each Post in the list do this
        foreach($posts as $post)
        {
          //get tags from the current Post
          $tags = explode(",", $post->tags);

          //if $tags variable contains one empty character (which means there are no tags for this Post)
          if(count($tags) == 1 && $tags[0] == "")
          { $post->tags = null; } //make it null
          else 
          { $post->tags = $tags; }

          //attach the name of the Category to current Post 
          $post->category = App\Category::find($post->category_id)->category_name;

          //count comments in current Post
          $post->comment_count = count(App\Comment::where('post_id','=', $post->id)->where('visibility','=', 1)->get());

          //if theres more than one comment
          if($post->comment_count > 1 || $post->comment_count == 0)
          { $post->comment_count .= " comments"; } //the label will be commentS
          else 
          { $post->comment_count .= " comment"; } //or commenT

          //get the list of files for current Post
          $media = App\Media::where('post_id','=', $post->id)->where('visibility','=',1)->get();

          //if current Post has files
          if(count($media) != 0)
          {     
                //add subtitles for each file
                foreach($media as $m)
                {
                    $subtitles = App\Subtitles::where('media_id','=',$m->id)->where('visibility','=','1')->get();
                    $m->subs = $subtitles;
                }
                
              //attach media files to current Post
              $post->media = $media;

              //attach media_type to current Post
              $post->media_type = $media[0]->media_type;
          }
        }
        
        return view('category_view', compact('category', 'posts','isMobile','view_type'));
    }

    //raise a Category in the list of Categories
    function raise_category(Request $request)
    {
        $category = App\Category::find($request->id);

        if($category != null)
        {  
            //if the category above is 0 ('blank' category)
            //do nothing and redirect back
            if($category->visual_order - 1 == 0)
            { return redirect()->back(); }
            else
            {
                //get the 'upper' category
                $category_upper = App\Category::where('visual_order','=', $category->visual_order - 1)->get()[0];
                
                //raise the current category
                $category->visual_order = $category->visual_order - 1;
                
                //lower the 'upper' category
                $category_upper->visual_order = $category_upper->visual_order + 1;

                //save changes
                $category->save();
                $category_upper->save();
                return redirect()->back();    
            }
        }
        else
        { return abort(500, "Couldn't get the category from the database."); }
    }
    
    //lower a Category in the list of Categories
    function lower_category(Request $request)
    {
        $category = App\Category::find($request->id);

        if($category != null)
        {
            //get id of the lowest Category in the list
            $lowest = App\Category::max('visual_order');

            //if after lowering of the category the id is bigger than max
            //do nothing, redirect back
            if($category->visual_order + 1 > $lowest)
            { return redirect()->back(); }
            else
            {
                //get 'lower'category
                $category_lower = App\Category::where('visual_order','=', $category->visual_order + 1)->get()[0];
            
                $category->visual_order = $category->visual_order + 1;
        
                $category_lower->visual_order = $category_lower->visual_order - 1;

                $category->save();
                $category_lower->save();

                return redirect()->back();    
            }
        }
        else
        { return abort(500, "Couldn't get the category from the database."); }


    }

}
