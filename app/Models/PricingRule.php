<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_list_id',
        'apply_to',
        'apply_id',
        'type_rule',
        'min_quantity',
        'value',
        'init_date',
        'expired_date',
        'active',
    ];

}
