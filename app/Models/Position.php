<?php

namespace App\Models;

use App\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'account' => 'string',
        'symbol' => 'string',
        'description' => 'string',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'price_change' => 'decimal:2',
        'value' => 'decimal:2',
        'today_gain_dollar' => 'decimal:2',
        'today_gain_percent' => 'decimal:2',
        'total_gain_dollar' => 'decimal:2',
        'total_gain_percent' => 'decimal:2',
        'percent_of_account' => 'decimal:2',
        'cost_basis' => 'decimal:2',
        'cost_basis_per_share' => 'decimal:2',
        'type' => 'string',
    ];

    public function getColorAttribute()
    {
        if ($this->symbol == 'CASH') {
            return '000000';
        }
        return substr(md5($this->symbol), 0, 6);
    }
}
