<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Illuminate\Http\Response;
use App\Globals\Globals;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //view the index page of the website
    //view all posts on the main page of the website
    public function index(Request $request)
    {
        //get current view type from cookies
        $view_type = $request->cookie('view_type');

        //pagination by default
        $paginate = 9;

        //pagination if view type is 'grid'
        if($view_type == 'grid')
        { $paginate = 27; }

        if($view_type == null)
        { $view_type = config('settings')->view_type; }

        //get all the visible posts and sort them by date (desc)
        $posts = App\Post::where('visibility','=','1')
            ->where('date','<=',Carbon::now()->format('Y-m-d'))
            ->orderBy('pinned','desc')->orderBy('date', 'desc')
            ->orderBy('id','desc')->paginate($paginate);

        if($posts != null)
        {
            foreach($posts as $post)
            {
                //get tags of a current post and attach them
                $tags= explode(",", $post->tags);
                //if $tags variable contains one empty character (which means there are no tags for this Post)
                //make it null
                if(count($tags) == 1 && $tags[0]=="")
                { $post->tags = null; } 
                else
                { $post->tags = $tags; }
    
                //attach the name of the Category to current Post 
                $post->category = App\Category::find($post->category_id)->category_name;
                
                //if it is the 'blank' category, it won't be displayed
                if($post->category == "blank") 
                { $post->category = ""; }
    
                //count comments in current Post
                $post->comment_count = count(App\Comment::where('post_id','=',$post->id)->where('visibility','=',1)->get());
    
                //if theres more than one comment
                //the label will be commentS
                //else, commenT
                if($post->comment_count > 1 || $post->comment_count == 0)
                { $post->comment_count .= " comments"; } 
                else 
                { $post->comment_count .= " comment"; }
                    
                //get the list of files for current Post
                $media = App\Media::where('post_id','=',$post->id)->where('visibility','=',1)->get();
                
                //if current Post has files
                if(count($media) != 0)
                {
                    //add subtitles for each file
                    foreach($media as $m)
                    {
                        $subs = App\Subtitles::where('media_id','=',$m->id)
                            ->where('visibility','=','1')
                            ->orderBy('display_name','asc')->get();
                        $m->subs = $subs;
                    }
    
                    //attach media files to current Post
                    $post->media = $media;
                    //attach media_type to current Post
                    $post->media_type = $media[0]->media_type;
                }
            }
    
            //for sorting by tag
            //set tag name to null to avoid error when posts aren't sorted by tag
            $tag_name = null;
            
            return view('home', compact('posts', 'tag_name', 'view_type'));
        }
    }

    //set 'view_type' cookie
    public function set_view_type(Request $request)
    {   
        //get current view type from cookies
        $current_view_type = $request->cookie('view_type');

        //if user visits the web site for the first time, set view_type cookie
        if($request->change_view == null)
        {   
            //if cookie was not set
            if($current_view_type == null)
            {   
                //get default view_type from settings
                $view_type_by_default = App\Settings::all()[0]->view_type;
                
                $response = new Response("The 'view_type' cookie has been set.");

                //set cookie
                $response->withCookie(cookie('view_type', $view_type_by_default, $view_type_by_default));

                return $response;
            }
            else
            {   
                //if value is null, do not set the cookie
                $response = new Response("The 'view_type' cookie was already set.");
                return $response;
            } 
        }

        //if it's not users first time and he wants to change the type of view
        if($request->change_view == true)
        {   
            $response = new Response("The 'view_type' cookie has been set.");
            
            //set cookie
            $response->withCookie(cookie('view_type', $request->view_type, $request->view_type));
            return $response;
        }
    }

    //check if user has accepted the cookies before
    public function check_cookies_accepted(Request $request)
    {   
        //if cookie 'visitedBefore' does not exist, this is the first visit
        $cookies_accepted = $request->cookie('cookiesAccepted');

        if($cookies_accepted == null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //set cookie 'cookiesAccepted' if user has acceped the cookies
    public function set_cookies_accepted(Request $request)
    {
        $response = new Response("The 'cookiesAccepted' cookie has been set.");

        $response->withCookie(cookie()->forever('cookiesAccepted', true));

        return $response;
    }

    //view 'About' page
    public function view_about_page()
    {
        $about_content = App\Settings::get()[0]->about_content;

        //if 'About' page set is not visible, redirect back
        if(config('settings')->show_about == 0)
        {
            return redirect()->back();
        }

        //if current user is Admin, the page will also show 'Edit About page' button
        $is_admin = Globals::check_admin();

        return view('about',compact('about_content','is_admin'));
    }
}
