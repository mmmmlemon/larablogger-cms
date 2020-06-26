<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;

//functions related to Categories

class CategoryController extends Controller
{
    //CONTROL PANEL
    //show Category list in control panel
    public function category_list ()
    {
        $categs = App\Category::where('category_name','!=','blank')->orderBy('visual_order','asc')->get();
        $max = App\Category::max('visual_order');
        return view('control_panel/categories/categories', compact('categs','max'));    
    }

    //show page 'Create category'
    public function show_create_category()
    {
        return view('control_panel/categories/add_category');
    }

    //create and save a Category
    public function create_category(Request $request)
    {
        $request->validate([
            'category_name' => 'string|max:50',
        ]);

        $categ = new App\Category;
        $categ->category_name = $request->category_name;
        $max = App\Category::max('visual_order');
        $categ->visual_order = $max + 1;
        $categ->save();
        return redirect(url('/control/categories'));
    }

    //show page 'Edit category'
    public function show_edit_category($id)
    {
        $categ = App\Category::find($id);
        return view('control_panel/categories/edit_category', compact('categ'));
    }
    
    //save changes in a Category
    public function edit_category(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'string|max:50',
        ]);

        $categ = App\Category::find($id);
        $categ->category_name = $request->category_name;
        $categ->save();
        return redirect(url('/control/categories'));
    }

    //delete a Category from the database
    public function delete_category(Request $request)
    {   
        //gets id of the 'blank' Category (to write this id in those posts that were added to the Category that is getting deleted)
        $blank_id = App\Category::where('category_name','=', 'blank')->first()->id;

        //get posts of a Category that is getting deleted
        $posts = App\Post::where('category_id','=', $request->modal_form_input)->get();
        
        //write the id of a 'blank' category to those posts
        foreach($posts as $post){
            $p = App\Post::find($post->id);
            $p->category_id = $blank_id;
            $p->save();
        }

        //deleting the Category from the database
        $categ = App\Category::find($request->modal_form_input);
        $categ->delete();

        return redirect(url('/control/categories'));
    }


    //POSTS DISPLAY
    //show Posts by a Category
    public function show_posts_by_category($category_name)
    {
        //get the Category by the name (from route url)
        $categ = App\Category::where('category_name','=',$category_name)->first();

        //get all the posts in this Category by its id
        $posts = App\Post::where('category_id','=',$categ->id)->where('visibility','=','1')->where('date','<=',Carbon::now()->format('Y-m-d'))->orderBy('date','desc')->orderBy('id','desc')->paginate(7);

        //for each Post in the list do this
        foreach($posts as $post)
        {
          //get tags from the current Post
          $tags = explode(",", $post->tags);
          //if $tags variable contains one empty character (which means there are no tags for this Post)
          if(count($tags) == 1 && $tags[0] == "")
          {$post->tags = null;} //make it null
          else 
          {$post->tags = $tags;}

          //attach the name of the Category to current Post 
          $post->category = App\Category::find($post->category_id)->category_name;

          //count comments in current Post
          $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());
          //if theres more than one comment
          if($post->comment_count > 1 || $post->comment_count == 0)
          {$post->comment_count .= " comments";} //the label will be commentS
          else 
          {$post->comment_count .= " comment";} //or commenT

          //get the list of files for current Post
          $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();

          //if current Post has files
          if(count($media) != 0)
          {     
                //add subtitles for each file
                foreach($media as $m)
                {
                    $subs = App\Subtitles::where('media_id','=',$m->id)->where('visibility','=','1')->get();
                    $m->subs = $subs;
                }
                
              //attach media files to current Post
              $post->media = $media;
              //attach media_type to current Post
              $post->media_type = $media[0]->media_type;
          }
        }
        
        return view('category_view', compact('categ', 'posts'));
    }

    //raise a Category in the list of Categories
    function raise_category(Request $request)
    {
        $categ = App\Category::find($request->id);

        //if the category above is 0 ('blank' category)
        if($categ->visual_order - 1 == 0)
        {
            return redirect()->back(); //do nothing and redirect back
        }
        else
        {
            //get the 'upper' category
            $categ_upper = App\Category::where('visual_order','=', $categ->visual_order - 1)->get()[0];
            
            //raise the current category
            $categ->visual_order = $categ->visual_order - 1;
            
            //lower the 'upper' category
            $categ_upper->visual_order = $categ_upper->visual_order + 1;

            //save changes
            $categ->save();
            $categ_upper->save();
            return redirect()->back();    
        }

    }
    
    //lower a Category in the list of Categories
    function lower_category(Request $request)
    {
        $categ = App\Category::find($request->id);

        //get id of the lowest Category in the list
        $lowest = App\Category::max('visual_order');

        //if after lowering of the category the id is bigger than max
        if($categ->visual_order + 1 > $lowest)
        {
            return redirect()->back(); //do nothing, redirect back
        }
        else
        {
            //get 'lower'category
            $categ_lower = App\Category::where('visual_order','=', $categ->visual_order + 1)->get()[0];
        
            $categ->visual_order = $categ->visual_order + 1;
      
            $categ_lower->visual_order = $categ_lower->visual_order - 1;

            $categ->save();
            $categ_lower->save();

            return redirect()->back();    
        }
    }

}
