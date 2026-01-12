<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PricingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'apply_to', //product,client
    ];

    public function rules()
    {
        return $this->hasMany(PricingRule::class);
    }
}
