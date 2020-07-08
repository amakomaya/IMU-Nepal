<?php

namespace App\Http\Resources\ContentApp;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ContentApp\NewsFeedResource;

class NewsFeedCollection extends ResourceCollection
{
    
    public function toArray($request)
    {
        $this->collection->transform(function ($request) {
            return (new NewsFeedResource($request));
        });

        return parent::toArray($request);
    }
}
