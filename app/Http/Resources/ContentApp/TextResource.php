<?php

namespace App\Http\Resources\ContentApp;

use Illuminate\Http\Resources\Json\JsonResource;

class TextResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_np' => $this->title_np,
            'description_en' => $this->description_en,
            'description_np' => $this->description_np,
            'week_id' => $this->week_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}