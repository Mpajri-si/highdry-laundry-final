<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['code','name','discount_type','discount_value','start_date','end_date','is_active'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

public function isValid(): bool
    {
        return $this->is_active && now()->between($this->start_date, $this->end_date->endOfDay());
    }

public function discountFor(float $subtotal): float
    {
        $d = $this->discount_type === 'persen'
        ? $subtotal * $this->discount_value / 100
        : $this->discount_value;
        return min($d, $subtotal);
    }
}
