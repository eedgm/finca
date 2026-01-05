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
        Schema::create('marking_cow', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cow_id');
            $table->unsignedBigInteger('marking_id');
            $table->timestamps();
            
            $table->foreign('cow_id')->references('id')->on('cows')->onDelete('cascade');
            $table->foreign('marking_id')->references('id')->on('markings')->onDelete('cascade');
            $table->unique(['cow_id', 'marking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marking_cow');
    }
};
