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
        Schema::table('cows', function (Blueprint $table) {
            $table->unsignedBigInteger('cow_type_id')->nullable();
            $table->foreign('cow_type_id')->references('id')->on('cow_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cows', function (Blueprint $table) {
            $table->dropForeign(['cow_type_id']);
            $table->dropColumn('cow_type_id');
        });
    }
};
