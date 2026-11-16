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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['kiloan', 'satuan']);
            $table->string('unit', 10)->default('kg');      // kg, pcs, pasang
            $table->decimal('price', 12, 2);
            $table->unsignedTinyInteger('estimated_days')->default(2);
            $table->text('description')->nullable();
            $table->string('image')->nullable();            // upload single
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
