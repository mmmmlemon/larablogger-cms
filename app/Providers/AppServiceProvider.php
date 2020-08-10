<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Jenssegers\Agent\Agent;
use Auth;
use View;

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
        //
        Schema::defaultStringLength(191);

        $agent = new Agent();
        $isMobile = $agent->isMobile();
        config(['isMobile' => $isMobile]);
    }
}
