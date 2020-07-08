<?php

namespace App\Http\Resources\ContentApp;
use App\Models\ContentApp\Multimedia;


use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ContentApp\VideoResource;


class VideoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function (Multimedia $user) {
            return (new VideoResource($user));
        });
        return parent::toArray($request);
    }
}