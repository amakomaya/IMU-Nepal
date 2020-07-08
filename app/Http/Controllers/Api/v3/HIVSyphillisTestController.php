<?php

namespace App\Http\Controllers\Api\v3;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\HIVSyphillisTestService;


class HIVSyphillisTestController extends Controller
{
    protected $service;

    public function __construct(HIVSyphillisTestService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function show(Request $request)
    {

    }

    public function update(Request $request)
    {
        return $this->service->update($request);
    }

    public function destroy($id)
    {
        //
    }

}
