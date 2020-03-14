<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

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

        return view('user/control_panel', compact('settings', 'social_media'));
    }

    //обновление общих настроек сайта
    public function update_settings(Request $request)
    {
        $request->validate([
            'site_title'=>'required',
            'site_subtitle'=>'required'
        ]);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->save();

        return redirect()->back();

        //dd($request->site_subtitle);
    }

    //обновление соц.сетей
    public function update_social(Request $request, $num)
    {
       for($i = 0; $i < $num; $i++){
           $id = $request->get('id_'. $i);
           $data = App\SocialMedia::where('id','=', $id)->first();

           if($data == null){
            //dd($request->get('platform_'.$i));
            $new_data = new App\SocialMedia;
            $new_data->platform_name =  $request->get('platform_'.$i);
            $new_data->url =  $request->get('url_'.$i);
            $new_data->save();
           }
           else{
            $data->platform_name = $request->get('platform_'.$i);
            $data->url = $request->get('url_'.$i);
            $data->save();
           }
         
          
       }

       return redirect()->back();
    }
}
 

