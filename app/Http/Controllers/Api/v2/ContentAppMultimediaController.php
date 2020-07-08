<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Api\ContentAppMultimediaService;

class ContentAppMultimediaController extends Controller
{
    protected $service;

    public function __construct(ContentAppMultimediaService $service)
    {
        $this->service = $service;
    }

    public function getText()
    {
        return $this->service->getText();
    }

    public function getVideo()
    {
        return $this->service->getVideo();
    }

    public function getAudio()
    {
        return $this->service->getAudio();
    }
}