<?php

namespace App\Observers;

use App\Events\AncWasCreated;
use App\Models\Anc;

class AncObserver
{
    public function creating(Anc $anc)
    {
//        event(new AncWasCreated($anc));
    }
}
