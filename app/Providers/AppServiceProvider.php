<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Dao\Video\VideoDaoInterface', 'App\Dao\Video\VideoDao');
        $this->app->bind('App\Contracts\Services\Video\VideoServiceInterface', 'App\Services\Video\VideoService');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
