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

//functions for the Admin control panel
class ControlPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //show Control panel
    public function show_control_panel()
    {   
        //get all the settings
        $settings = App\Settings::all()->first(); //general settings
        $social_media = App\SocialMedia::all(); //social media
        $users = App\User::orderBy('user_type','asc')->paginate(15)->fragment('users'); //users list
        $current_user = Auth::user(); //current user

        return view('control_panel/control_panel', compact('settings', 'social_media', 'users', 'current_user'));
    }


    //GENERAL SETTINGS
    //update general web-site settings
    public function update_settings(Request $request)
    {
        $request->validate([
            'site_title'=>'required|max:55',
            'site_subtitle'=>'required|max:55',
            'contact_email'=>'email|nullable',
            'from_email' => 'email',
            'contact_text'=>'string|max:200|nullable'
        ]);

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
            'platform_0'=>'max:20|nullable',
            'platform_1'=>'max:20|nullable',
            'platform_2'=>'max:20|nullable',
            'platform_3'=>'max:20|nullable',
            'url_0'=>'url|nullable',
            'url_1'=>'url|nullable',
            'url_2'=>'url|nullable',
            'url_3'=>'url|nullable',
        ]);
       
       //pass through for loop four times (because there 4 fields for social media) 
       //and rewrite the info about social media
       for($i = 0; $i < 4; $i++)
       {
           $id = $request->get('id_'. $i);
           $data = App\SocialMedia::where('id','=', $id)->first();
           //if such data exists, fill it with the new info
           if($data != null)
           {
              $data->platform_name = $request->get('platform_'.$i);
              $data->url = $request->get('url_'.$i);
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
            'user_type' => 'string|in:admin,user',
        ]);
            
        $user = App\User::find($request->user_id);

        //if user exists and he is an Admin, make him a common user
        if($user != null && $request->user_type == 'admin')
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

        return redirect(url()->previous() . "#users");
    }

    //DESIGN
    //save design changes
    public function update_design(Request $request)
    {
        //get site settings
        $settings = App\Settings::get()[0];

        $validator = Validator::make($request->all(), [
            'background_image' => 'mimes:jpeg,jpg,png|max:3000',
            'footer_content' => 'string|max:500'
        ]);

        if($validator->fails()){
            return redirect(url()->previous()."#design")->withErrors($validator)->withInput();
        }

        //save background image
        //if the form has an image
        if($request->background_image != null)
        {
            if($validator->fails()){
                return redirect(url()->previous()."#design")->withErrors($validator)->withInput();
            }
            
            //generate random file name with a number (0 to 99) and the original extension
            $filename = "bg_".rand(0,99).".".$request->file('background_image')->getClientOriginalExtension();
            $img = Image::make($request->background_image); //create image
            $img->fit(1920,1080); //fit image into 1920x1080 resolution

            //if 'Blue Image' or/and 'Darken Image' were check
            //blur or/and darken the image
            if($request->blur_img == "on")
            {$img->blur(85);}
            if($request->dark_img == "on")
            {
                $img->brightness(-25);
                $img->contrast(-20);
            }

            //delete the old background image
            $files = File::files(storage_path("app/public/images/bg"));

            foreach($files as $file)
            {
                unlink($file->getPathname());
            }

            //save the new image
            $img->save(storage_path('/app/public/images/bg/').$filename);
            $settings->bg_image = "/images/bg/"."/".$filename; //write path to image into the site settings
        }

        //if Show About page is (un)checked, then (un)check it
        if($request->show_about == "on")
        { $settings->show_about = 1; }
        else { $settings->show_about = 0; }

        $settings->save();

        return redirect()->to("/control#design");
    }

    //show page Edit About
    public function show_edit_about()
    {
        if(Auth::check() && Auth::user()->user_type != 0)
        {
            return redirect('/');
        }
        $content = App\Settings::get()[0]->about_content;
        return view('/control_panel/edit_about', compact('content'));
    }

    //save changes in About page
    public function save_about(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'about_content' => 'string|max:5000'
        ]);

        if($validator->fails()){
            return redirect("/control/edit_about")->withErrors($validator)->withInput();
        }

        $settings = App\Settings::get()[0];
        $settings->about_content = $request->about_content;

        $settings->save();

        return redirect()->to('/control#design');
    }

    //PROFILE
    //update user profile
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:25',
            'email' => 'required|email',
            'password' => 'min:8|confirmed|nullable',
            'password_confirmation' => 'min:8'
        ]);

        if($validator->fails()){
            return redirect(url()->previous() . "#profile")->withErrors($validator)->withInput();
        }

        $user = App\User::find(Auth::user()->id);
        
        $user->name = $request->username;
        $user->email = $request->email;

        if($request->password != null)
        {
           $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect(url()->previous() . "#profile");
        
    }


    //COMMENTS
    public function view_comments(){
        $comments = App\Comment::orderBy('created_at','desc')->paginate(20);

        foreach($comments as $c){
            $post_title = App\Post::where('id','=',$c->post_id)->first()->post_title;
            $c->comment_content = strip_tags($c->comment_content);
            $c->post_title = $post_title;
        }
        
        return view('control_panel/comments/comments', compact('comments'));
    }
    
}
 

