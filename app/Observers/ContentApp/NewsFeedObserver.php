<?php

namespace App\Observers\ContentApp;
use App\Models\ContentApp\NewsFeed;
use Carbon\Carbon;

class NewsFeedObserver
{
   public function creating(NewsFeed $newsFeed)
   {
   	if (!$newsFeed->token) {
      	$newsFeed->token = \Auth::user()->token;
   	}

   	if ($newsFeed->status == 1) {
   		$newsFeed->published_at = Carbon::now();
   	}
   }

   public function updating(NewsFeed $newsFeed)
    {
      if($newsFeed->status == 1){
        !empty($newsFeed->published_at) ? $newsFeed->published_at : Carbon::now();
      }
    }
}