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
            // Remove old color and markings columns if they exist
            if (Schema::hasColumn('cows', 'color')) {
                $table->dropColumn('color');
            }
            if (Schema::hasColumn('cows', 'markings')) {
                $table->dropColumn('markings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cows', function (Blueprint $table) {
            $table->json('color')->nullable();
            $table->json('markings')->nullable();
        });
    }
};
