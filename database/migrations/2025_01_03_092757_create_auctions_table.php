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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('car_name');
            $table->string('model');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->integer('passenger_capacity');
            $table->string('body_style'); // e.g., sedan
            $table->integer('cylinders');
            $table->string('color');
            $table->string('engine_type');
            $table->string('transmission');
            $table->string('vehicle_type'); // auto or manual
            $table->string('fuel'); // diesel or petrol
            $table->text('damage_description')->nullable();
            $table->decimal('starting_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
