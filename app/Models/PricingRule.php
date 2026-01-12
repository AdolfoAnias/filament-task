<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'apply_to',
        'client_id',
        'product_id',
        'type_rule',
        'min_quantity',
        'value',
        'init_date',
        'expired_date',
        'active',
    ];

    public function pricingList()
    {
        return $this->belongsTo(PricingList::class);
    }

}
