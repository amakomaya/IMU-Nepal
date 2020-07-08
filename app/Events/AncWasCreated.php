<?php

namespace App\Events;

use App\Models\Anc;
use Illuminate\Queue\SerializesModels;

class AncWasCreated
{
    use SerializesModels;
    public $anc;

    public function __construct(Anc $array)
    {
        $this->anc = $array;
    }
}