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
        Schema::create('color_cow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cow_id');
            $table->unsignedBigInteger('color_id');
            $table->timestamps();
            
            $table->foreign('cow_id')->references('id')->on('cows')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->unique(['cow_id', 'color_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_cow');
    }
};
