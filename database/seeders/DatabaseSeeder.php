<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    User::create([
        'name' => 'Admin High&Dry', 'email' => 'admin@highdry.test',
        'password' => Hash::make('password'), 'role' => 'admin',
    ]);
    User::create([
        'name' => 'Budi Pelanggan', 'email' => 'budi@mail.test',
        'password' => Hash::make('password'), 'role' => 'pelanggan',
        'phone' => '081234567890', 'address' => 'Jl. Contoh No. 1, Pekanbaru',
    ]);

    $services = [
        ['Cuci Kiloan',   'kiloan', 'kg',     7000,  2],
        ['Cuci + Setrika','kiloan', 'kg',     9000,  3],
        ['Setrika Saja',  'kiloan', 'kg',     5000,  1],
        ['Cuci Selimut',  'satuan', 'pcs',   25000,  3],
        ['Cuci Sepatu',   'satuan', 'pasang',30000,  4],
        ['Dry Cleaning',  'satuan', 'pcs',   20000,  3],
    ];
    foreach ($services as [$name,$type,$unit,$price,$days]) {
        Service::create([
            'name'=>$name,'type'=>$type,'unit'=>$unit,'price'=>$price,
            'estimated_days'=>$days,'is_active'=>true,
            'description'=>"Layanan $name dengan hasil bersih dan wangi.",
        ]);
    }

    Promo::create([
        'code'=>'HEMAT10','name'=>'Diskon 10% Pelanggan Baru',
        'discount_type'=>'persen','discount_value'=>10,
        'start_date'=>now()->startOfMonth(),'end_date'=>now()->addMonths(2),
        'is_active'=>true,
          ]);
    }
}
