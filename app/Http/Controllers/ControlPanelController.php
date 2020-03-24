<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Validator;
use Carbon\Carbon;

class ControlPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = App\Settings::all()->first();
        $social_media = App\SocialMedia::all();
        $users = App\User::orderBy('user_type','asc')->paginate(15)->fragment('users');
        $current_user = Auth::user();

        return view('control_panel/control_panel', compact('settings', 'social_media', 'users', 'current_user'));
    }

    //обновление общих настроек сайта
    public function update_settings(Request $request)
    {
        $request->validate([
            'site_title'=>'required|max:25',
            'site_subtitle'=>'required|max:55',
            'contact_email'=>'required|email'
        ]);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->contact_email = $request->get('contact_email');
        $settings->save();

        return redirect()->back();

        //dd($request->site_subtitle);
    }

    //обновление соц.сетей
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
       
       //четыре раза прогоняем цикл for (потому что четыре поля для соц сетей) 
       //и перезаписываем информацию о соц. сетях
       for($i = 0; $i < 4; $i++){
           $id = $request->get('id_'. $i);
           $data = App\SocialMedia::where('id','=', $id)->first();
            
           if($data == null){
            // $new_data = new App\SocialMedia;
            // $new_data->platform_name =  $request->get('platform_'.$i);
            // $new_data->url =  $request->get('url_'.$i);
            // $new_data->save();
           }
           else{
            $data->platform_name = $request->get('platform_'.$i);
            $data->url = $request->get('url_'.$i);
            $data->save();
           }   
       }

       return redirect()->back();
    }

    public function change_user_type(Request $request){
        
        $request->validate([
            'user_type' => 'string|in:admin,user',
        ]);
            
        $user = App\User::find($request->user_id);

        if($user != null && $request->user_type == 'admin'){
            $user->user_type = 2;
            $user->save();
        }
        else if ($user != null && $request->user_type == 'user'){
            $user->user_type = 1;
            $user->save();
        }
        else{
            //do nothing
        }

        return redirect(url()->previous() . "#users");
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:25',
            'email' => 'required|email'
        ]);

        //если валидатор фейлит, то редиректим назад с якорем
        if($validator->fails()){
            return redirect(url()->previous() . "#profile")->withErrors($validator)->withInput();
        }

        $user = App\User::find(Auth::user()->id)->first();
        
        $user->name = $request->username;
        $user->email = $request->email;

        $user->save();

        return redirect(url()->previous() . "#profile");
        
    }

    public function create_post(Request $request)
    {
    //     $request->validate([
    //         'post_title' => 'string|max:35',
    //         'post_content' => 'string',
    //         'publish' => 'integer|max: 1',
    //         'publish_date' => 'date'
    //     ]);

        $post = new App\Post;
        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        if($request->publish = 'on'){
            $post->status = 1;
            $post->date = Carbon::now()->format('Y-m-d');
        } else {
            $post->status = 0;
            $post->date = $request->publish_date;
        }

        $post->save();
        //dd($post);
       return redirect(url('/control'));
    }


}
 

