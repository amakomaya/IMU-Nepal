<?php

namespace Yagiten\Nepalicalendar;

use Illuminate\Support\ServiceProvider;

class NepaliCalendarProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Yagiten\Nepalicalendar\Calendar');
    }
}
