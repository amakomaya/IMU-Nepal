<?php

namespace App\Events;

use App\Models\Woman;
use Illuminate\Queue\SerializesModels;

class WomanWasCreated
{
    use SerializesModels;
    public $woman;

    public function __construct(Woman $woman)
    {
        $this->woman = $woman;
    }
}
