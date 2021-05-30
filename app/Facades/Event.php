<?php
namespace App\Facades;
use Illuminate\Support\Facades\Event as IlluminateEvent;

class Event extends IlluminateEvent
{

    public static function fire($var)
    {
        parent::dispatch($var);
    }
}