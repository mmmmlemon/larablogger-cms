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

    //вывод страницы панели управления
    public function index()
    {   
        //получаем данные необходимые на этой странице
        $settings = App\Settings::all()->first(); //общие настройки сайта
        $social_media = App\SocialMedia::all(); //соц. сети
        $users = App\User::orderBy('user_type','asc')->paginate(15)->fragment('users'); //список пользователей
        $current_user = Auth::user(); //текущий пользователь

        return view('control_panel/control_panel', compact('settings', 'social_media', 'users', 'current_user'));
    }

    //обновление общих настроек сайта
    public function update_settings(Request $request)
    {
        $request->validate([
            'site_title'=>'required|max:25',
            'site_subtitle'=>'required|max:55',
            'contact_email'=>'required|email',
            'contact_email'=>'string|max:200',
            'contact_email'=>'string|max:500',
        ]);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->contact_email = $request->get('contact_email');
        $settings->contact_text = $request->get('contact_text');
        $settings->footer_text = $request->get('footer_text');
        $settings->save();

        return redirect()->back();
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
           //если такая запись о соц. сети существует то обновляем её
           if($data != null){
                $data->platform_name = $request->get('platform_'.$i);
                $data->url = $request->get('url_'.$i);
                $data->save();
           }
       }

       return redirect()->back();
    }

    //изменить тип пользователя
    public function change_user_type(Request $request){
        
        $request->validate([
            'user_type' => 'string|in:admin,user',
        ]);
            
        $user = App\User::find($request->user_id);

        //если пользователь существует и он админ, то делаем его просто юзером
        if($user != null && $request->user_type == 'admin'){
            $user->user_type = 2;
            $user->save();
        }//если существует и просто юзер, то делаем его админом
        else if ($user != null && $request->user_type == 'user'){
            $user->user_type = 1;
            $user->save();
        }//если ни то, ни то, то ничего не делаем
        else{
            //do nothing
        }

        return redirect(url()->previous() . "#users");
    }

    //обновить настройки профиля
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

    public function edit_about(){

        $content = App\Settings::get()[0]->about_content;

        return view('/control_panel/edit_about', compact('content'));
    }

    public function save_about(Request $request){
        $settings = App\Settings::get()[0];
        $settings->about_content = $request->about_content;

        $settings->save();

        return redirect()->to('/control#design');
    }
}
 

