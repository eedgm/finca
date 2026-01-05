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
            $table->string('color')->nullable()->after('picture');
            $table->string('markings')->nullable()->after('color'); // Marcas distintivas, manchas, etc.
            $table->decimal('birth_weight', 8, 2)->nullable()->after('born'); // Peso al nacer en kg
            $table->decimal('height', 5, 2)->nullable()->after('birth_weight'); // Altura a la cruz en cm
            $table->text('observations')->nullable()->after('height'); // Observaciones y otras características
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cows', function (Blueprint $table) {
            $table->dropColumn(['color', 'markings', 'birth_weight', 'height', 'observations']);
        });
    }
};
