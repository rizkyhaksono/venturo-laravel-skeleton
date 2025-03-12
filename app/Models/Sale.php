<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sale extends Model
{
  use HasFactory;

  protected $table = 't_sales';
  public $incrementing = false;
  protected $keyType = 'string';

  protected $fillable = ['id', 'm_customer_id', 'date'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      $model->id = (string) Str::uuid();
    });
  }

  public function details()
  {
    return $this->hasMany(SaleDetail::class, 't_sales_id');
  }

  public function customer()
  {
    return $this->belongsTo(CustomerModel::class, 'm_customer_id');
  }

  public function user()
  {
    return $this->belongsTo(UserModel::class, 'm_user_id');
  }
}
