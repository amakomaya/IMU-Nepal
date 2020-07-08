<?php

namespace App\Models\ContentApp;

use Illuminate\Database\Eloquent\Model;

/**
 * @method active()
 */
class NewsFeed extends Model
{
    protected $table = 'news_feed';

    protected $fillable = ['token', 'author', 'title', 'url', 'url_to_image', 'status', 'published_at'];

    protected $dates = ['published_at'];
 
 	public function scopeActive($query){
	    return $query->where('status', 1);
	}
}