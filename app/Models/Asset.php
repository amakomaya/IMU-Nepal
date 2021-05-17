<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockTransaction;

class Asset extends Model
{
  public $table = 'assets';

  protected $dates = [
      'created_at',
      'updated_at',
  ];

  protected $fillable = [
      'name',
      'created_at',
      'updated_at',
      'description',
      'danger_level',
  ];

  public function transactions()
  {
      return $this->hasMany(StockTransaction::class, 'asset_id');
  }
}
