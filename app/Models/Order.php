<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number','customer_id','admin_id','promo_id','delivery_method',
        'pickup_address','notes','status','discount','total',
    ];

    public const STATUSES = ['Menunggu Verifikasi','Diproses','Dicuci','Disetrika','Selesai'];

    public function customer()  { return $this->belongsTo(User::class, 'customer_id'); }
    public function admin()     { return $this->belongsTo(User::class, 'admin_id'); }
    public function promo()     { return $this->belongsTo(Promo::class); }
    public function items()     { return $this->hasMany(OrderItem::class); }
    public function payment()   { return $this->hasOne(Payment::class); }
    public function histories() { return $this->hasMany(StatusHistory::class); }
    public function photos()    { return $this->hasMany(OrderPhoto::class); }
}