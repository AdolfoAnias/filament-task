<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderItems extends Model
{
    protected $fillable = [
        'sale_order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
