<?php

namespace App\Providers;

use App\Models\Anc;
use App\Observers\AncObserver;
use Illuminate\Support\ServiceProvider;

class AncServiceProvider extends ServiceProvider
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
        Anc::observe(AncObserver::class);
    }
}
