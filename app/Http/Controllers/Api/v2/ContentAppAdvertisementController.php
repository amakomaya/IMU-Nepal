<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ContentAppAdvertisementService;

class ContentAppAdvertisementController extends Controller
{
    protected $service;

    public function __construct(ContentAppAdvertisementService $service)
    {
        $this->service = $service;
    }

    public function get()
    {
        return $this->service->getAll();
    }
}