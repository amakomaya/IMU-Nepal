<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;
use App\Models\Organization;
use App\User;

class StockTransaction extends Model
{
  public $table = 'stock_transactions';

  protected $dates = [
      'created_at',
      'updated_at',
  ];

  protected $fillable = [
      'stock',
      'org_code',
      'user_id',
      'asset_id',
      'current_stock',
      'new_stock',
      'used_stock',
      'created_at',
      'updated_at',
  ];

  public function asset()
  {
      return $this->belongsTo(Asset::class, 'asset_id');

  }

  public function healthpost()
  {
      return $this->belongsTo(Organization::class, 'org_code', 'org_code');

  }

  public function user()
  {
      return $this->belongsTo(User::class, 'user_id');
  }

  protected $supportedRelations = ['healthpost', 'asset', 'user'];

  public function scopeWithAll($query)
  {
      return $query->with($this->supportedRelations);
  }
}
