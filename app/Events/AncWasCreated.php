<?php

namespace App\Events;

use App\Models\SampleCollection;
use Illuminate\Queue\SerializesModels;

class AncWasCreated
{
    use SerializesModels;
    public $anc;

    public function __construct(SampleCollection $array)
    {
        $this->anc = $array;
    }
}