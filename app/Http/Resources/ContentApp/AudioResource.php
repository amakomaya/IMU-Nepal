<?php

namespace App\Http\Resources\ContentApp;

use Illuminate\Http\Resources\Json\JsonResource;

class AudioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_np' => $this->title_np,
            'thumbnail' => env('APP_URL', 'http://aamakomaya.com').$this->thumbnail,
            'path' => env('APP_URL', 'http://aamakomaya.com').$this->path,
            'week_id' => $this->week_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}