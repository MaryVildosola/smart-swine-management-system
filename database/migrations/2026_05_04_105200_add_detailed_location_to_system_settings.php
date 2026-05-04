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
        // Add detailed location settings
        $settings = [
            [
                'key' => 'farm_island_group',
                'value' => 'Luzon',
                'type' => 'select',
                'group' => 'biosecurity',
                'label' => 'Island Group',
                'description' => 'Major island group (Luzon, Visayas, or Mindanao).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'farm_region_name',
                'value' => 'CALABARZON',
                'type' => 'string',
                'group' => 'biosecurity',
                'label' => 'Administrative Region',
                'description' => 'Official region name (e.g., Region IV-A, Central Visayas).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'farm_province',
                'value' => 'Batangas',
                'type' => 'string',
                'group' => 'biosecurity',
                'label' => 'Province',
                'description' => 'Specific province where the farm is located.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'farm_city',
                'value' => 'Lipa City',
                'type' => 'string',
                'group' => 'biosecurity',
                'label' => 'City / Municipality',
                'description' => 'The specific city or town of the farm operation.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('system_settings')->insert($settings);
        
        // Remove the old generic setting
        DB::table('system_settings')->where('key', 'default_farm_region')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the old setting if rolled back
        DB::table('system_settings')->insert([
            'key' => 'default_farm_region',
            'value' => 'Philippines',
            'type' => 'string',
            'group' => 'biosecurity',
            'label' => 'Default Farm Region',
            'description' => 'The primary geographic region for the AI Disease Scout to monitor.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('system_settings')->whereIn('key', ['farm_island_group', 'farm_region_name', 'farm_province', 'farm_city'])->delete();
    }
};
