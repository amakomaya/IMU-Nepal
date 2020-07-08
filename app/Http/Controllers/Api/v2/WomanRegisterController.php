<?php

namespace App\Http\Controllers\Api\v2;

use App\Services\Api\WomanRegisterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WomanRegisterController extends Controller
{
    protected $service;
    public function __construct(WomanRegisterService $service){
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }
}
