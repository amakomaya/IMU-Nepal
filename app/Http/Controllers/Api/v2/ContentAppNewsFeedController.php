<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Api\ContentAppNewsFeedService;


class ContentAppNewsFeedController extends Controller
{
    protected $service;

    public function __construct(ContentAppNewsFeedService $service)
    {
        $this->service = $service;
    }

    public function getNewsFeed($token = null)
    {
        try {
            $find_hp_code_by_woman_token = \App\Models\Woman::where('token',$token)->first()->hp_code;

            $hp_info = \App\Models\Healthpost::where('hp_code', $find_hp_code_by_woman_token)->first();
            $healthpost_token = $hp_info->token;
            $municipality_token = \App\Models\MunicipalityInfo::where('municipality_id', $hp_info->municipality_id)->first()->token;
            $main_tokens = \App\User::where('role', 'main')->pluck('token');
            $tokens = collect([$municipality_token, $healthpost_token])->merge($main_tokens);
            return $this->service->getNewsFeed($tokens);

        }catch(\Exception $e){
            return $this->service->getNewsFeed();
        }
        
    }
}