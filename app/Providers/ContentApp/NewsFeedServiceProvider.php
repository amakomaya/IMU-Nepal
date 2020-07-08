<?php

namespace App\Providers\ContentApp;

use Illuminate\Support\ServiceProvider;

class NewsFeedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\ContentApp\NewsFeed::observe(\App\Observers\ContentApp\NewsFeedObserver::class);
    }
}
