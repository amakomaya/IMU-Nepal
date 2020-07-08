<?php

namespace App\Http\Resources\ContentApp;
use App\Models\ContentApp\Multimedia;


use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ContentApp\AudioResource;


class AudioCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function (Multimedia $user) {
            return (new AudioResource($user));
        });
        return parent::toArray($request);
    }
}