<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\AncService;


class AncController extends Controller
{
    protected $service;

    public function __construct(AncService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }
}
