<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Validator;
use Carbon\Carbon;
use Image;
use File;
use Hash;
use DB;
use Storage;
use App\Globals\Globals;

//functions for the Admins control panel
class ControlPanelController extends Controller
{
    public function __construct()
    {

    }

    //View control panel
    public function show_control_panel()
    {
        //get all the settings
        //general settings
        $settings = App\Settings::all()->first(); 

        //social media
        $social_media = App\SocialMedia::all(); 
        
        //users list
        $users = App\User::orderBy('user_type', 'asc')->paginate(15)->fragment('users'); 

        //current user
        $current_user = Auth::user(); 

        if($settings != null)
        {
            if(count($social_media) > 0)
            { 
                if(count($users) > 0)
                {
                    if($current_user != null)
                    {
                        return view('control_panel/control_panel', compact('settings', 'social_media', 'users', 'current_user')); 
                    }
                    else
                    { return abort(500, "Couldn't get the current user."); }
                }
                else
                { return abort(500, "Couldn't get the users list from the database."); }
            }
            else
            { return abort(500, "Couldn't get social the media list from the database."); } 
        }
        else
        { return abort(500, "Couldn't get the settings from the database."); } 
    }

    //GENERAL SETTINGS
    //update general web-site settings
    public function update_settings(Request $request)
    {
        $request->validate([
        'site_title' => 'required|max:55', 
        'site_subtitle' => 'required|max:55', 
        'contact_email' => 'email|nullable', 
        'from_email' => 'email', 
        'contact_text' => 'string|max:200|nullable']);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->contact_email = $request->get('contact_email');
        $settings->from_email = $request->get('from_email');
        $settings->contact_text = $request->get('contact_text');

        $settings->save();

        return redirect()->to('/control#settings');
    }

    //update list of social media
    public function update_social(Request $request)
    {
        $request->validate([
        'platform_0' => 'max:20|nullable', 
        'platform_1' => 'max:20|nullable', 
        'platform_2' => 'max:20|nullable', 
        'platform_3' => 'max:20|nullable', 
        'url_0' => 'url|nullable', 
        'url_1' => 'url|nullable', 
        'url_2' => 'url|nullable', 
        'url_3' => 'url|nullable']);

        //pass through for loop four times (because there are 4 fields for social media)
        //and rewrite the info about social media
        for ($i = 0; $i < 4; $i++)
        {
            $id = $request->get('id_' . $i);
            $data = App\SocialMedia::where('id', '=', $id)->first();

            //if such data exists, fill it with the new info
            if ($data != null)
            {
                $data->platform_name = $request->get('platform_' . $i);
                $data->url = $request->get('url_' . $i);
                $data->save();
            }
        }

        return redirect()->to('/control#settings');
    }

    //USERS
    //change user type
    public function change_user_type(Request $request)
    {
        $request->validate([
            'user_id' => 'int', 
            'user_type' => 'string|in:admin,user']);

        $user = App\User::find($request->user_id);

        //if user exists and he is an Admin, make him a common user
        if ($user != null && $request->user_type == 'admin')
        {
            $user->user_type = 2;
            $user->save();
        }
        //if user exists and he is a common user, make him an Admin
        else if ($user != null && $request->user_type == 'user')
        {
            $user->user_type = 1;
            $user->save();
        }
        //do nothing if both conditions were not met
        else
        { 
            //
        }

        return redirect(url()->previous() . "#users");
    }

    //DESIGN
    //save design changes
    public function update_design(Request $request)
    {
        //get site settings
        $settings = App\Settings::all()->first();

        $validator = Validator::make($request->all(), [
         'background_image' => 'mimes:jpeg,jpg,png|max:3000', 
         'footer_content' => 'string|max:500']);

        if ($validator->fails())
        { return redirect(url()->previous() . "#design")->withErrors($validator)->withInput(); }

        //save background image
        //if the form has an image
        if ($request->background_image != null)
        {
            if ($validator->fails())
            { return redirect(url()->previous() . "#design")->withErrors($validator)->withInput(); }

            //generate random file name with a number (0 to 99) and the original extension
            $filename = "bg_" . rand(0, 99) . "." . $request->file('background_image')->getClientOriginalExtension();
            $img = Image::make($request->background_image); //create image
            $img->fit(1920, 1080); //fit image into 1920x1080 resolution
            //if 'Blue Image' or/and 'Darken Image' is check
            //blur or/and darken the image
            if ($request->blur_img == "on")
            { $img->blur(85); }
            if ($request->dark_img == "on")
            {
                $img->brightness(-25);
                $img->contrast(-20);
            }

            //delete the old background image
            $files = File::files(storage_path("app/public/images/bg"));

            foreach ($files as $file)
            { unlink($file->getPathname()); }

            //save the new image
            $img->save(storage_path('/app/public/images/bg/') . $filename);
            $settings->bg_image = "/images/bg/" . "/" . $filename; //write path to image into the site settings  
        }

        //if Show About page is (un)checked, then (un)check it
        if ($request->show_about == "on")
        { $settings->show_about = 1; }
        else
        { $settings->show_about = 0; }

        $settings->save();

        return redirect()->to("/control#design");
    }

    //show page Edit About
    public function show_edit_about()
    {
        if (Auth::check() && Auth::user()->user_type != 0)
        { return redirect('/'); }
        $content = config('settings')->about_content;
        return view('/control_panel/edit_about', compact('content'));
    }

    //save changes in About page
    public function save_about(Request $request)
    {
        $validator = Validator::make($request->all(), ['about_content' => 'string|max:5000']);

        if ($validator->fails())
        {
            return redirect("/control/edit_about")
                ->withErrors($validator)->withInput();
        }

        $settings = App\Settings::all()->first();

        if($settings != null)
        {
            $settings->about_content = $request->about_content;
            $settings->save();
    
            return redirect()->to('/control#design');
        }
        else
        { return abort(500, "Couldn't get the settings from the database."); }

    }

    //PROFILE
    //update user profile
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
         'username' => 'required|string|max:25', 
         'email' => 'required|email', 
         'password' => 'min:8|confirmed|nullable']);

        if ($validator->fails())
        { return redirect(url()->previous() . "#profile")->withErrors($validator)->withInput(); }

        $user = App\User::find(Auth::user()->id);

        if($user != null)
        {
            $user->name = $request->username;
            $user->email = $request->email;
    
            if($request->password != null)
            { $user->password = Hash::make($request->password); }
    
            $user->save();
    
            return redirect(url()->previous() . "#profile");
        }
        else
        { return abort(500, "Could't get the user from the database"); }



    }

    //COMMENTS
    //show list of all comments
    public function view_comments()
    {
        $comments = App\Comment::orderBy('created_at', 'desc')->paginate(20);

        foreach ($comments as $comment)
        {
            if ($comment->is_logged_on != - 1)
            {
                $comment->username = App\User::where('id', '=', 1)->get()[0]->name;
            }
            $post_title = App\Post::where('id', '=', $comment->post_id)->first()->post_title;
            $comment->comment_content = str_replace("&nbsp;", " ", strip_tags($comment->comment_content));
            $comment->post_title = $post_title;
        }

        return view('control_panel/comments/comments', compact('comments'));
    }

    //SEARCH FUNCTIONS
    //search first three results
    public function simple_search(Request $request)
    {   
        //search value
        $val = $request->value;
        //search results
        $result = "";
        //check if admin
        $is_admin = Globals::check_admin();

        //POST SEARCH
        if ($request->type == "post")
        {
            //if user, search only for visible posts
            if ($is_admin == false)
            {
                $result = DB::table('posts')->select('id', 'post_title', 'post_content', 'category_id', 'date')
                    ->where('visibility', '=', 1)->where(function ($query) use ($val){
                        $query->where('post_title', 'like', '%' . $val . '%');
                        $query->orWhere('post_content', 'like', '%' . $val . '%');
                    })->orderBy('id', 'desc')->take(3)->get();
            }
            //if admin, search for all posts
            else if ($is_admin == true)
            {
                $result = DB::table('posts')->select('id', 'post_title', 'post_content', 'category_id', 'date')
                    ->where('post_title', 'like', '%' . $val . '%')
                    ->orWhere('post_content', 'like', '%' . $val . '%')
                    ->orderBy('id', 'desc')->take(3)->get();
            }

            //for each found post, do this
            foreach ($result as $r)
            {
                $r->post_content = strip_tags($r->post_content); //remove HTML tags from post contents
                $r->post_content = str_replace('&nbsp;', '', $r->post_content); //remove &nbsp
                $r->date = date('d.m.Y', strtotime($r->date)); //format date
                $r->category = App\Category::find($r->category_id)->category_name; //add category name

                $pos = strpos(strtolower($r->post_content) , strtolower($request->value));  //find the position of the search value

                if (strlen($r->post_content) < 120)
                {
                    //if post content is less than 120 characters, do nothing and show full post_content    
                }
                //if the search value was not found
                else if ($pos === false)
                {
                    $dots_end = "...";
                    if (strlen($r->post_content) <= 100)
                    {
                        $dots_end = "";
                    }
                    //add (or do not add) triple dots at the end of the post content
                    $r->post_content = substr($r->post_content, 0, 100) . $dots_end;
                }
                //if search value was found
                else
                {
                    //the closest position of space character near the search value
                    $space_pos = strrpos(substr($r->post_content, 0, $pos - 5) , " ");
                    $dots_start = "...";
                    $dots_end = "...";
                    if ($space_pos <= 0)
                    { $dots_start = ""; }
                    
                    if ($space_pos + 100 >= strlen($r->post_content))
                    { $dots_end = ""; }
                    //add triple dots at the start and the end of post content (or don't)
                    $r->post_content = $dots_start . substr($r->post_content, $space_pos, $space_pos + 100) . $dots_end;
                }
            }
        }
    
        return json_encode($result);
    }

    //search full results
    public function full_search(Request $request)
    {   
        $view_type = $request->cookie('view_type');
        $result = "";
        $is_admin = Globals::check_admin();

        if ($view_type == null)
        { $view_type = config('settings')->view_type; }

        //search value
        $val = $request->search_value;

        //if search value is null, redirect back
        if ($val == null)
        { return redirect()->back(); }

        //POST SEARCH
        if ($request->type == "post")
        {   
            //type of search request (search post, search comments, search media etc.)
            $type = $request->type;

            //if user isn't admin, show only visible posts
            if ($is_admin == false)
            {
                $results = DB::table('posts')->select('id', 'post_title', 'post_content', 'category_id', 'date')
                    ->where('visibility', '=', 1)->where(function ($query) use ($val){
                    $query->where('post_title', 'like', '%' . $val . '%');
                    $query->orWhere('post_content', 'like', '%' . $val . '%');
                    })->orderBy('id', 'desc')->get();
            }
            //if user is admin, show all posts
            else if ($is_admin == true)
            {
                $results = App\Post::where('post_title', 'like', '%' . $val . '%')
                    ->orWhere('post_content', 'like', '%' . $val . '%')
                    ->orderBy('id', 'desc')->get();
            }
            
            //for each search result
            foreach ($results as $r)
            {
                $r->post_content = strip_tags($r->post_content); //remove HTML tags
                $r->post_content = str_replace('&nbsp;', '', $r->post_content); //remove &nbsp
                $r->date = date('d.m.Y', strtotime($r->date)); //format date
                $r->category = App\Category::find($r->category_id)->category_name; //add category name

                $pos = strpos(strtolower($r->post_content) , strtolower($val));  //find the position of the search value

                if (strlen($r->post_content) < 120)
                {
                    //if post content is less than 120 characters, do nothing and show full post_content  
                }

                else if ($pos === false)
                {
                    $dots_end = "...";
                    if(strlen($r->post_content) <= 100)
                    { $dots_end = ""; }
                    $r->post_content = substr($r->post_content, 0, 100) . $dots_end;
                }
                else
                {
                    $space_pos = strrpos(substr($r->post_content, 0, $pos - 5), " ");
                    $dots_start = "...";
                    $dots_end = "...";
                    if($space_pos <= 0)
                    { $dots_start = ""; }

                    if($space_pos + 100 >= strlen($r->post_content))
                    { $dots_end = ""; }
                    $r->post_content = $dots_start . substr($r->post_content, $space_pos, $space_pos + 100) . $dots_end;
                }
            }

            //if its search from the main page
            if($request->is_control_panel == null)
            { return view('search/search', compact('results', 'view_type', 'val')); }
            //if its search from the control panel
            else if ($request->is_control_panel == "true")
            {   
                //if admin, show results
                if($is_admin == true)
                { return view('search/search_control_panel', compact('results','val', 'type')); }
                else
                { return redirect('/'); }
            }
        }
        //SEARCH COMMENT
        else if ($request->type == "comment")
        {
            $type = $request->type;
            $val = $request->search_value;
            if($is_admin == true)
            {
                $results = App\Comment::select('comments.post_id as post_id','comments.comment_content as comment_content',
                'comments.username as username', 'comments.date as date', 'comments.id as id', 'comments.deleted as deleted',
                'comments.visibility as visibility','users.name as real_username')
                ->leftJoin('users','users.id','=','comments.is_logged_on')
                ->where('name','like','%'.$val.'%')->orWhere('username','like','%'.$val.'%')
                ->orWhere('comment_content','like','%'.$val.'%')->orderBy('date','desc')->get();
           
                foreach($results as $res)
                { $res->post_title = App\Post::where('id','=',$res->post_id)->get()->first()->post_title; }

                return view('search/search_control_panel', compact('results','val','type'));
            }
            else
            { abort(403, 'Unauthorized action'); }
        }
        
        //SEARCH MEDIA
        else if ($request->type == "media")
        {
            $type = $request->type;
            $val = $request->search_value;
            $results = App\Media::select('media.id as id', 'media.display_name as display_name',
            'media.actual_name as actual_name', 'media.created_at as created_at', 'media.media_type as media_type',
            'media.media_url as media_url', 'media.thumbnail_url as thumbnail_url', 'posts.post_title as post_title', 
            'posts.id as post_id', 'posts.post_title as post_title')
            ->leftJoin('posts','posts.id','=','media.post_id')
            ->where('display_name','like','%'.$val.'%')
            ->orWhere('actual_name','like','%'.$val.'%')
            ->orWhere('post_title','like','%'.$val.'%')->orderBy('created_at','desc')->get();

            return view('search/search_control_panel', compact('results','val','type'));
        }

        //else, redirect to main page
        else
        { return redirect('/'); }

    }
}

