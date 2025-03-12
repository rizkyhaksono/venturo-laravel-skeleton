<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
  use HasFactory;

  protected $table = 't_sales_detail';
  protected $fillable = ['t_sales_id', 'm_product_id', 'm_product_detail_id', 'total_item', 'price'];

  public function sale()
  {
    return $this->belongsTo(Sale::class, 't_sales_id');
  }

  public function product()
  {
    return $this->belongsTo(ProductModel::class, 'm_product_id');
  }
}
