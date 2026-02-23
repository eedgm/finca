<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sale_animals', function (Blueprint $table) {
            $table->decimal('weight_kg', 10, 2)->nullable()->change();
            $table->decimal('price_per_kg_usd', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sale_animals', function (Blueprint $table) {
            $table->decimal('weight_kg', 10, 2)->nullable(false)->change();
            $table->decimal('price_per_kg_usd', 10, 2)->nullable(false)->change();
        });
    }
};
