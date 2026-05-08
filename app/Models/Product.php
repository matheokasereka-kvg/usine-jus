<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'unit', 'stock_quantity', 'sale_price', 'alert_threshold'];

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
