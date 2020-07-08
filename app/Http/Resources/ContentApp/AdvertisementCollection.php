<?php

namespace App\Http\Resources\ContentApp;
use App\Models\ContentApp\Advertisement;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ContentApp\AdvertisementResource;


class AdvertisementCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function (Advertisement $user) {
            return (new AdvertisementResource($user));
        });
        return parent::toArray($request);
    }
}