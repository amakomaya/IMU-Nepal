<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\ContentAppNotificationService;

class ContentAppNotificationController extends Controller
{
	protected $service;

    public function __construct(ContentAppNotificationService $service){
        $this->service = $service;
    } 

    public function getNotification(Request $request)
    {
    	return $this->service->getNotification($request);
    }

	public function postNotification(Request $request){
		return $this->service->postNotification($request);
	}

	public function updateReadAt(Request $request){
        return $this->service->updateReadAt($request);
    }
}