<?php

namespace App\Events;

use App\Models\SuspectedCase;
use Illuminate\Queue\SerializesModels;

class WomanWasCreated
{
    use SerializesModels;
    public $woman;

    public function __construct(SuspectedCase $woman)
    {
        $this->woman = $woman;
    }
}
