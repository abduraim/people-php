<?php

namespace App\Providers;

use App\Services\HtmlParser\HtmlParser;
use App\Services\NewsResources\Rbc;
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
        $this->app->singleton(HtmlParser::class, function () {
            return new HtmlParser(new Rbc());
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
    }
}
