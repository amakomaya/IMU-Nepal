<?php

namespace App\Http\Resources\ContentApp;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsFeedResource extends JsonResource
{
    public function toArray($request)
    {
        try {
            $extension = pathinfo(public_path($this->url_to_image))['extension'];

            $image_extensions = ['jpg','png','jpeg','gif'];

            if (in_array(strtolower($extension), $image_extensions)) {
                $urlToImage = env('APP_URL', 'http://aamakomaya.com').$this->url_to_image;
            }else{
                $urlToVideo = env('APP_URL', 'http://aamakomaya.com').$this->url_to_image;
            }
        }catch (\Exception $extension){

        }
        

        return [
            'id' => $this->id,
            'author' => $this->author,
            'title' => $this->title,
            'url' => $this->url,
            'urlToVideo' => $urlToVideo ?? '',
            'urlToImage' => $urlToImage ?? '',
            'publishedAt' => empty($this->published_at) ? $this->published_at : $this->published_at->diffForHumans()
        ];
    }
}

