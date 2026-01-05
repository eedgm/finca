<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename table if it exists with the old name
        if (Schema::hasTable('cow_breed')) {
            Schema::rename('cow_breed', 'breed_cow');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename back if needed
        if (Schema::hasTable('breed_cow')) {
            Schema::rename('breed_cow', 'cow_breed');
        }
    }
};
