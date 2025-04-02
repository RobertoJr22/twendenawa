<?php

namespace App\Providers;

use App\Helpers\dados;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\Helper;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar o Helper para ser usado em todas as views
        View::composer('*', function ($view) {
            $view->with('helper', new Helper);
        });

        URL::forceScheme('https');

    }
}
