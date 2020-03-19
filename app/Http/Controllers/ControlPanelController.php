<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;

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

        return view('user/control_panel', compact('settings', 'social_media', 'users', 'current_user'));
    }

    //обновление общих настроек сайта
    public function update_settings(Request $request)
    {
        $request->validate([
            'site_title'=>'required',
            'site_subtitle'=>'required',
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
        $request->validate([
            'username' => 'required|string|max:15',
            'email' => 'required|email'
        ]);

        $user = App\User::find(Auth::user()->id)->first();
        
        $user->name = $request->username;
        $user->email = $request->email;

        $user->save();

        return redirect(url()->previous() . "#profile");
        
    }
}
 

