<?php

namespace App\Http\Resources\ContentApp;
use App\Models\ContentApp\Multimedia;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ContentApp\TextResource;


class TextCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function (Multimedia $multimedia) {
            return (new TextResource($multimedia));
        });

        return parent::toArray($request);
    }
}
