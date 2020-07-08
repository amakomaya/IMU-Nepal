<?php

namespace App\Models\ContentApp;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'advertisements';

    protected $hidden = ['id'];

    public $timestamps = false;

    protected $fillable = ['token', 'type', 'url','plan', 'expire_date', 'external_url'];

    public function getTypeAttribute(){
	    return config('advertisement.type')[$this->attributes['type']];
	} 

	public function getPlanAttribute(){
	    return config('advertisement.plan')[$this->attributes['plan']];
	} 

}
