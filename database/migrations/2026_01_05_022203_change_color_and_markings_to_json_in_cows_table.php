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
        Schema::table('cows', function (Blueprint $table) {
            // Change color and markings to JSON to support multiple values
            $table->json('color')->nullable()->change();
            $table->json('markings')->nullable()->change();
        });
        
        // Migrate existing string data to JSON array format
        // Handle migration safely for both MySQL and other databases
        try {
            $cows = DB::table('cows')->whereNotNull('color')->where('color', '!=', '')->get();
            foreach ($cows as $cow) {
                // Check if already JSON
                $decoded = json_decode($cow->color, true);
                if (!is_array($decoded)) {
                    DB::table('cows')->where('id', $cow->id)->update([
                        'color' => json_encode([$cow->color])
                    ]);
                }
            }
            
            $cows = DB::table('cows')->whereNotNull('markings')->where('markings', '!=', '')->get();
            foreach ($cows as $cow) {
                // Check if already JSON
                $decoded = json_decode($cow->markings, true);
                if (!is_array($decoded)) {
                    DB::table('cows')->where('id', $cow->id)->update([
                        'markings' => json_encode([$cow->markings])
                    ]);
                }
            }
        } catch (\Exception $e) {
            // If migration fails, continue (might be fresh install or different DB)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON arrays back to strings (take first element)
        try {
            $cows = DB::table('cows')->whereNotNull('color')->get();
            foreach ($cows as $cow) {
                $decoded = json_decode($cow->color, true);
                if (is_array($decoded) && !empty($decoded)) {
                    DB::table('cows')->where('id', $cow->id)->update([
                        'color' => $decoded[0] ?? null
                    ]);
                }
            }
            
            $cows = DB::table('cows')->whereNotNull('markings')->get();
            foreach ($cows as $cow) {
                $decoded = json_decode($cow->markings, true);
                if (is_array($decoded) && !empty($decoded)) {
                    DB::table('cows')->where('id', $cow->id)->update([
                        'markings' => $decoded[0] ?? null
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Continue if migration fails
        }
        
        Schema::table('cows', function (Blueprint $table) {
            $table->string('color')->nullable()->change();
            $table->string('markings')->nullable()->change();
        });
    }
};
