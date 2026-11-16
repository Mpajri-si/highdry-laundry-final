<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();       // EW-0001 / HD-0001
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->foreignId('promo_id')->nullable()->constrained('promos');
            $table->enum('delivery_method', ['antar_sendiri', 'dijemput']);
            $table->text('pickup_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('Menunggu Verifikasi');
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
