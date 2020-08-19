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

//functions for the Admin control panel
class ControlPanelController extends Controller
{

    private function check_admin()
    {
        if (Auth::check())
        {
            if (Auth::user()->user_type == 0 || Auth::user()->user_type == 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function __construct()
    {

    }

    //show Control panel
    public function show_control_panel()
    {
        //get all the settings
        $settings = App\Settings::all()->first(); //general settings
        $social_media = App\SocialMedia::all(); //social media
        $users = App\User::orderBy('user_type', 'asc')->paginate(15)
            ->fragment('users'); //users list
        $current_user = Auth::user(); //current user
        return view('control_panel/control_panel', compact('settings', 'social_media', 'users', 'current_user'));
    }

    //GENERAL SETTINGS
    //update general web-site settings
    public function update_settings(Request $request)
    {
        $request->validate(['site_title' => 'required|max:55', 'site_subtitle' => 'required|max:55', 'contact_email' => 'email|nullable', 'from_email' => 'email', 'contact_text' => 'string|max:200|nullable']);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->contact_email = $request->get('contact_email');
        $settings->from_email = $request->get('from_email');
        $settings->contact_text = $request->get('contact_text');

        $settings->save();

        return redirect()
            ->to('/control#settings');
    }

    //update list of social media
    public function update_social(Request $request)
    {
        $request->validate(['platform_0' => 'max:20|nullable', 'platform_1' => 'max:20|nullable', 'platform_2' => 'max:20|nullable', 'platform_3' => 'max:20|nullable', 'url_0' => 'url|nullable', 'url_1' => 'url|nullable', 'url_2' => 'url|nullable', 'url_3' => 'url|nullable', ]);

        //pass through for loop four times (because there 4 fields for social media)
        //and rewrite the info about social media
        for ($i = 0;$i < 4;$i++)
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

        return redirect()
            ->to('/control#settings');
    }

    //USERS
    //change user type
    public function change_user_type(Request $request)
    {
        $request->validate(['user_id' => 'int', 'user_type' => 'string|in:admin,user', ]);

        $user = App\User::find($request->user_id);

        //if user exists and he is an Admin, make him a common user
        if ($user != null && $request->user_type == 'admin')
        {
            $user->user_type = 2;
            $user->save();
        }
        //if user exists and he's a common user, make him an Admin
        else if ($user != null && $request->user_type == 'user')
        {
            $user->user_type = 1;
            $user->save();
        }
        //do nothing if both conditions were not met
        else
        {
            //do nothing
            
        }

        return redirect(url()
            ->previous() . "#users");
    }

    //DESIGN
    //save design changes
    public function update_design(Request $request)
    {
        //get site settings
        $settings = App\Settings::get() [0];

        $validator = Validator::make($request->all() , ['background_image' => 'mimes:jpeg,jpg,png|max:3000', 'footer_content' => 'string|max:500']);

        if ($validator->fails())
        {
            return redirect(url()
                ->previous() . "#design")
                ->withErrors($validator)->withInput();
        }

        //save background image
        //if the form has an image
        if ($request->background_image != null)
        {
            if ($validator->fails())
            {
                return redirect(url()
                    ->previous() . "#design")
                    ->withErrors($validator)->withInput();
            }

            //generate random file name with a number (0 to 99) and the original extension
            $filename = "bg_" . rand(0, 99) . "." . $request->file('background_image')
                ->getClientOriginalExtension();
            $img = Image::make($request->background_image); //create image
            $img->fit(1920, 1080); //fit image into 1920x1080 resolution
            //if 'Blue Image' or/and 'Darken Image' were check
            //blur or/and darken the image
            if ($request->blur_img == "on")
            {
                $img->blur(85);
            }
            if ($request->dark_img == "on")
            {
                $img->brightness(-25);
                $img->contrast(-20);
            }

            //delete the old background image
            $files = File::files(storage_path("app/public/images/bg"));

            foreach ($files as $file)
            {
                unlink($file->getPathname());
            }

            //save the new image
            $img->save(storage_path('/app/public/images/bg/') . $filename);
            $settings->bg_image = "/images/bg/" . "/" . $filename; //write path to image into the site settings
            
        }

        //if Show About page is (un)checked, then (un)check it
        if ($request->show_about == "on")
        {
            $settings->show_about = 1;
        }
        else
        {
            $settings->show_about = 0;
        }

        $settings->save();

        return redirect()
            ->to("/control#design");
    }

    //show page Edit About
    public function show_edit_about()
    {
        if (Auth::check() && Auth::user()->user_type != 0)
        {
            return redirect('/');
        }
        $content = App\Settings::get() [0]->about_content;
        return view('/control_panel/edit_about', compact('content'));
    }

    //save changes in About page
    public function save_about(Request $request)
    {
        $validator = Validator::make($request->all() , ['about_content' => 'string|max:5000']);

        if ($validator->fails())
        {
            return redirect("/control/edit_about")
                ->withErrors($validator)->withInput();
        }

        $settings = App\Settings::get() [0];
        $settings->about_content = $request->about_content;

        $settings->save();

        return redirect()
            ->to('/control#design');
    }

    //PROFILE
    //update user profile
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all() , ['username' => 'required|string|max:25', 'email' => 'required|email', 'password' => 'min:8|confirmed|nullable']);

        if ($validator->fails())
        {
            return redirect(url()
                ->previous() . "#profile")
                ->withErrors($validator)->withInput();
        }

        $user = App\User::find(Auth::user()->id);

        $user->name = $request->username;
        $user->email = $request->email;

        if ($request->password != null)
        {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect(url()
            ->previous() . "#profile");

    }

    //COMMENTS
    public function view_comments()
    {
        $comments = App\Comment::orderBy('created_at', 'desc')->paginate(20);

        foreach ($comments as $c)
        {
            if ($c->is_logged_on != - 1)
            {
                $c->username = App\User::where('id', '=', 1)
                    ->get() [0]->name;
            }
            $post_title = App\Post::where('id', '=', $c->post_id)
                ->first()->post_title;
            $c->comment_content = str_replace("&nbsp;", " ", strip_tags($c->comment_content));
            $c->post_title = $post_title;
        }

        return view('control_panel/comments/comments', compact('comments'));
    }

    //SEARCH FUNCTIONS
    //search first three results
    public function simple_search(Request $request)
    {
        $val = $request->value;
        $result = "";
        $is_admin = $this->check_admin();

        //POST SEARCH
        if ($request->type == "post")
        {
            //if user, search only for visible posts
            if ($is_admin == false)
            {
                $result = DB::table('posts')->select('id', 'post_title', 'post_content', 'category_id', 'date')
                    ->where('visibility', '=', 1)->where(function ($query) use ($val)
                {
                    $query->where('post_title', 'like', '%' . $val . '%');
                    $query->orWhere('post_content', 'like', '%' . $val . '%');
                })->orderBy('id', 'desc')
                    ->get();
            }
            else if ($is_admin == true)
            {
                $result = DB::table('posts')->select('id', 'post_title', 'post_content', 'category_id', 'date')
                    ->where('post_title', 'like', '%' . $val . '%')->orWhere('post_content', 'like', '%' . $val . '%')->orderBy('id', 'desc')
                    ->take(3)
                    ->get();
            }

            foreach ($result as $r)
            {
                $r->post_content = strip_tags($r->post_content);
                $r->post_content = str_replace('&nbsp;', '', $r->post_content);
                $r->date = date('d.m.Y', strtotime($r->date));
                $r->category = App\Category::find($r->category_id)->category_name;

                $pos = strpos(strtolower($r->post_content) , strtolower($request->value));

                if (strlen($r->post_content) < 120)
                {
                    //if post content is less than 120 characters, do nothing and show full post_content
                    
                }
                else if ($pos === false)
                {
                    $dots_end = "...";
                    if (strlen($r->post_content) <= 100)
                    {
                        $dots_end = "";
                    }
                    $r->post_content = substr($r->post_content, 0, 100) . $dots_end;
                }
                else
                {
                    $space_pos = strrpos(substr($r->post_content, 0, $pos - 5) , " ");
                    $dots_start = "...";
                    $dots_end = "...";
                    if ($space_pos <= 0)
                    {
                        $dots_start = "";
                    }
                    //dd($space_pos+100);
                    if ($space_pos + 100 >= strlen($r->post_content))
                    {
                        $dots_end = "";
                    }
                    $r->post_content = $dots_start . substr($r->post_content, $space_pos, $space_pos + 100) . $dots_end;
                }
            }
        }
        //SEARCH COMMENT
        else if ($request->type == "comment")
        {
            if ($is_admin == true)
            {
                $result = App\Comment::where("is_logged_on", "=", -1)->where('username', 'like', '%' . $val . '%')->orWhere('comment_content', 'like', '%' . $val . '%')->get();
            }
            else
            {
                return "Authentication needed";
            }
        }

        return json_encode($result);
    }

    //search full results
    public function full_search(Request $request)
    {
        $view_type = $request->cookie('view_type');
        $result = "";
        $is_admin = $this->check_admin();

        if ($view_type == null)
        {
            $view_type = App\Settings::all() [0]->view_type;
        }

        $val = $request->search_value;

        if ($val == null)
        {
            return redirect()->back();
        }

        //POST SEARCH
        if ($request->type == "post")
        {
            $type = $request->type;
            if ($is_admin == false)
            {
                $results = DB::table('posts')->select('id', 'post_title', 'post_content', 'category_id', 'date')
                    ->where('visibility', '=', 1)->where(function ($query) use ($val)
                {
                    $query->where('post_title', 'like', '%' . $val . '%');
                    $query->orWhere('post_content', 'like', '%' . $val . '%');
                })->orderBy('id', 'desc')
                    ->get();
            }
            else if ($is_admin == true)
            {
                $results = App\Post::where('post_title', 'like', '%' . $val . '%')->orWhere('post_content', 'like', '%' . $val . '%')->orderBy('id', 'desc')
                    ->get();
            }

            foreach ($results as $r)
            {
                $r->post_content = strip_tags($r->post_content);
                $r->post_content = str_replace('&nbsp;', '', $r->post_content);
                $r->date = date('d.m.Y', strtotime($r->date));
                $r->category = App\Category::find($r->category_id)->category_name;

                $pos = strpos(strtolower($r->post_content) , strtolower($val));

                if (strlen($r->post_content) < 120)
                {
                    //if post content is less than 120 characters, do nothing and show full post_content  
                }

                else if ($pos === false)
                {
                    $dots_end = "...";
                    if (strlen($r->post_content) <= 100)
                    {
                        $dots_end = "";
                    }
                    $r->post_content = substr($r->post_content, 0, 100) . $dots_end;
                }
                else
                {
                    $space_pos = strrpos(substr($r->post_content, 0, $pos - 5) , " ");
                    $dots_start = "...";
                    $dots_end = "...";
                    if ($space_pos <= 0)
                    {
                        $dots_start = "";
                    }

                    if ($space_pos + 100 >= strlen($r->post_content))
                    {
                        $dots_end = "";
                    }
                    $r->post_content = $dots_start . substr($r->post_content, $space_pos, $space_pos + 100) . $dots_end;
                }
            }

            //if its search from the main page
            if ($request->is_control_panel == null)
            {
                return view('search/search', compact('results', 'view_type', 'val'));
            }
            //if its search from the control panel
            else if ($request->is_control_panel == "true")
            {
                if ($is_admin == true)
                {

                    return view('search/search_control_panel', compact('results','val', 'type'));
                }
                else
                {
                    return redirect('/');
                }
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
                'comments.username as username', 'comments.date as date', 'comments.id as id', 
                'comments.visibility as visibility','users.name as real_username')
                ->leftJoin('users','users.id','=','comments.is_logged_on')
                ->where('name','like','%'.$val.'%')->orWhere('username','like','%'.$val.'%')
                ->orWhere('comment_content','like','%'.$val.'%')->orderBy('date','desc')->get();
           
                foreach($results as $res)
                {
                    $res->post_title = App\Post::where('id','=',$res->post_id)->get()->first()->post_title;
                }

                return view('search/search_control_panel', compact('results','val','type'));
            }
            else
            {
                abort(403, 'Unauthorized action');
            }
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
        {
            return redirect('/');
        }

    }


    //SAVE MANUALLY UPLOADED MEDIA FILES
    public function save_uploaded_media_files(Request $request){

        //get list of files in the temp folder
        $temp_files = json_decode($request->file_list);
        $folder_name = "uploaded_manually/".date("M Y");
        //check if folder has been created
        $check = File::exists(storage_path("app/public/".$folder_name));

        if($check != true)
        {
            Storage::disk('public')->makeDirectory($folder_name);
        }

        foreach($temp_files as $file)
        {
           
            //path for replacement
            $new_path = storage_path("app/public/").$folder_name."/".$file->actual_filename;

            $move = File::move(storage_path("app/public/temp/".$file->actual_filename), $new_path);
        
            //if replacement failed, redirect back with error
            if($move != true) 
            {return Redirect::back()->withErrors(['err', 'Something went wrong while moving the files.']);}
            else
            {   
                //save the post before saving the media (to extract $post->id)
                // $media = new App\Media; //write media into the database
                // $media->post_id = null;
                // $media->media_url = "posts/". $folder_name."/".$file;
                // $media->media_type = $mime;
                // $media->display_name = $file;
                // $media->actual_name = $file;
                // $media->visibility = 1;
                // $media->save(); 
            }
        }   

    }

}

