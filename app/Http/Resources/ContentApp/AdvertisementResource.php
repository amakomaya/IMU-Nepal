<?php

namespace App\Http\Resources\ContentApp;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
        	'token' => $this->token,
            'type' => array_search($this->type, config('advertisement.type')),
            'plan' => array_search($this->plan, config('advertisement.plan')),
            'url' => env('APP_URL', 'http://aamakomaya.com').$this->url,
            'external_url' => $this->external_url,
            'expire_date' => $this->expire_date
        ];
    }
}