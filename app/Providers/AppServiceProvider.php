<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Jenssegers\Agent\Agent;
use Auth;
use View;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('path.public', function() {
            return base_path('public');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Schema::defaultStringLength(191);

        //check if mobile or desktop
        $agent = new Agent();
        $isMobile = $agent->isMobile();
        config(['isMobile' => $isMobile]);

        //site title
        $site_title = App\Settings::all()[0]->site_title;
        config(['site_title' => $site_title]);

        //app.blade
        //settings
        $settings = App\Settings::all()->first();
        config(['settings' => $settings]);

        //social media
        $social_media = App\SocialMedia::whereNotNull('platform_name')->whereNotNull('url')->get();
        config(['social_media' => $social_media]);

        //categoires
        $categories = App\Category::where('category_name','!=','blank')->orderBy('visual_order','asc')->get();
        config(['categories' => $categories]);
    }
}
