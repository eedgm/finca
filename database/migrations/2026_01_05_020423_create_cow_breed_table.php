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
        Schema::create('breed_cow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cow_id');
            $table->unsignedBigInteger('breed_id');
            $table->decimal('percentage', 5, 2)->default(0); // Porcentaje de la raza (0-100)
            $table->timestamps();

            $table->foreign('cow_id')->references('id')->on('cows')->onDelete('cascade');
            $table->foreign('breed_id')->references('id')->on('breeds')->onDelete('cascade');
            $table->unique(['cow_id', 'breed_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breed_cow');
    }
};
