<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cow_history', function (Blueprint $table) {
            $table
                ->foreign('history_id')
                ->references('id')
                ->on('histories')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('cow_id')
                ->references('id')
                ->on('cows')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cow_history', function (Blueprint $table) {
            $table->dropForeign(['history_id']);
            $table->dropForeign(['cow_id']);
        });
    }
};
