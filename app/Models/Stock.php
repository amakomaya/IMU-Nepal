<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;
use App\Models\Organization;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $dates = [
      'created_at',
      'updated_at',
    ];

    protected $fillable = [
      'org_code',
      'asset_id',
      'current_stock',
      'created_at',
      'updated_at',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');

    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_code', 'org_code');
    }
  
    protected $supportedRelations = ['organization', 'asset'];

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }
}
