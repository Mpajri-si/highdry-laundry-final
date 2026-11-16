<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name','type','unit','price','estimated_days','description','image','is_active'];
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
