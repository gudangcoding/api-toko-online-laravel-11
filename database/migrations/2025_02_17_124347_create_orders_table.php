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
            $table->foreignId('user_id');
            $table->integer('total');
            $table->enum('status', ["pending","paid","shipped","delivered","canceled"])->default('pending');
            $table->enum('payment_status', ["unpaid","paid"]);
            $table->enum('shipping_status', ["pending","shipped","delivered","canceled"])->default('pending');
            $table->foreignId('shipping_id');
            $table->foreignId('payment_id');
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
