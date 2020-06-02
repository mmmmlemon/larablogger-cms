<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use Validator;
use Carbon\Carbon;
use Image;
use File;

class ControlPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //вывод страницы панели управления
    public function show_control_panel()
    {   
        //получаем данные необходимые на этой странице
        $settings = App\Settings::all()->first(); //общие настройки сайта
        $social_media = App\SocialMedia::all(); //соц. сети
        $users = App\User::orderBy('user_type','asc')->paginate(15)->fragment('users'); //список пользователей
        $current_user = Auth::user(); //текущий пользователь

        return view('control_panel/control_panel', compact('settings', 'social_media', 'users', 'current_user'));
    }


    //ОБЩИЕ НАСТРОЙКИ
    //обновление общих настроек сайта
    public function update_settings(Request $request)
    {
        $request->validate([
            'site_title'=>'required|max:25',
            'site_subtitle'=>'required|max:55',
            'contact_email'=>'required|email',
            'contact_text'=>'string|max:200'
        ]);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->contact_email = $request->get('contact_email');
        $settings->contact_text = $request->get('contact_text');
        $settings->save();

        return redirect()->to('/control#settings');
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
       for($i = 0; $i < 4; $i++)
       {
           $id = $request->get('id_'. $i);
           $data = App\SocialMedia::where('id','=', $id)->first();
           //если такая запись о соц. сети существует то обновляем её
           if($data != null)
           {
              $data->platform_name = $request->get('platform_'.$i);
              $data->url = $request->get('url_'.$i);
              $data->save();
           }
       }

       return redirect()->to('/control#settings');
    }

    //ПОЛЬЗОВАТЕЛИ
    //изменить тип пользователя
    public function change_user_type(Request $request)
    {    
        $request->validate([
            'user_id' => 'int',
            'user_type' => 'string|in:admin,user',
        ]);
            
        $user = App\User::find($request->user_id);

        //если пользователь существует и он админ, то делаем его просто юзером
        if($user != null && $request->user_type == 'admin')
        {
            $user->user_type = 2;
            $user->save();
        }
        //если существует и просто юзер, то делаем его админом
        else if ($user != null && $request->user_type == 'user')
        {
            $user->user_type = 1;
            $user->save();
        }
        //если ни то, ни то, то ничего не делаем
        else
        {
            //do nothing
        }

        return redirect(url()->previous() . "#users");
    }

    //ПРОФИЛЬ
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

    //ДИЗАЙН
    //сохранить изменения в дизайне
    public function update_design(Request $request)
    {
        //получаем настройки сайта
        $settings = App\Settings::get()[0];

        $validator = Validator::make($request->all(), [
            'background_image' => 'mimes:jpeg,jpg,png|max:3000',
            'footer_content' => 'string|max:500'
        ]);

        if($validator->fails()){
            return redirect(url()->previous()."#design")->withErrors($validator)->withInput();
        }

        //сохранение bg image, фонового изображения
        //если в форму была добавлена картинка
        if($request->background_image != null)
        {
            if($validator->fails()){
                return redirect(url()->previous()."#design")->withErrors($validator)->withInput();
            }
    
            //генерируем новое имя файла из рандомной цифры от 0 до 99 + оригинальное расширение файла
            $filename = "bg_".rand(0,99).".".$request->file('background_image')->getClientOriginalExtension();
            $img = Image::make($request->background_image); //создаем изображение
            $img->fit(1920,1080); //меняем разрешение на 1920х1080

            //если в форме были отмечены Blur Image и(ли) Darken Image
            //то размываем и(ли) затемняем изображение
            if($request->blur_img == "on")
            {$img->blur(85);}
            if($request->dark_img == "on")
            {
                $img->brightness(-25);
                $img->contrast(-20);
            }

            //удаляем старый фон
            $files = File::files(storage_path("app\\public\\images\\bg"));
            foreach($files as $file)
            {
                unlink($file->getPathname());
            }

            //сохраняем получившееся изображение в папке для фоновых изображений
            $img->save(storage_path('\\app\\public\\')."/images/bg/"."/".$filename);
            $settings->bg_image = "/images/bg/"."/".$filename; //записываем путь до картинки в настройки
        }

        //если отмечен пункт Show About page, то меняем этот пункт в настройках (или нет)
        if($request->show_about == "on")
        { $settings->show_about = 1; }
        else { $settings->show_about = 0; }

        //текст для футера
        $settings->footer_text = $request->footer_content;

        $settings->save();

        return redirect()->to("/control#design");
    }

    //показать страницу Edit About
    public function show_edit_about()
    {
        if(Auth::check() && Auth::user()->user_type != 0)
        {
            return redirect('/');
        }
        $content = App\Settings::get()[0]->about_content;
        return view('/control_panel/edit_about', compact('content'));
    }

    //сохранить изменения на странице edit_about
    public function save_about(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'about_content' => 'string|max:2000'
        ]);

        if($validator->fails()){
            return redirect("/control/edit_about")->withErrors($validator)->withInput();
        }

        $settings = App\Settings::get()[0];
        $settings->about_content = $request->about_content;

        $settings->save();

        return redirect()->to('/control#design');
    }

}
 

