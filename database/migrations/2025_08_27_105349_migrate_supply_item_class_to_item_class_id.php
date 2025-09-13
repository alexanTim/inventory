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
        // First, ensure we have the necessary item classes
        $consumableClass = \App\Models\ItemClass::firstOrCreate(
            ['name' => 'consumable'],
            ['description' => 'Consumable items that are used up or depleted']
        );
        
        $accessoriesClass = \App\Models\ItemClass::firstOrCreate(
            ['name' => 'accessories'],
            ['description' => 'Accessory items that are not consumed']
        );
        
        // Update existing supply profiles to use the new item_class_id
        \DB::table('supply_profiles')->where('supply_item_class', 'consumable')->update([
            'item_class_id' => $consumableClass->id
        ]);
        
        \DB::table('supply_profiles')->where('supply_item_class', 'accessories')->update([
            'item_class_id' => $accessoriesClass->id
        ]);
        
        // Remove the old supply_item_class column
        Schema::table('supply_profiles', function (Blueprint $table) {
            $table->dropColumn('supply_item_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the old supply_item_class column
        Schema::table('supply_profiles', function (Blueprint $table) {
            $table->string('supply_item_class')->nullable();
        });
        
        // Restore the old values
        $consumableClass = \App\Models\ItemClass::where('name', 'consumable')->first();
        if ($consumableClass) {
            DB::table('supply_profiles')->where('item_class_id', $consumableClass->id)->update([
                'supply_item_class' => 'consumable'
            ]);
        }
        
        $accessoriesClass = \App\Models\ItemClass::where('name', 'accessories')->first();
        if ($accessoriesClass) {
            DB::table('supply_profiles')->where('item_class_id', $accessoriesClass->id)->update([
                'supply_item_class' => 'accessories'
            ]);
        }
        
        // Remove the item_class_id column
        Schema::table('supply_profiles', function (Blueprint $table) {
            $table->dropForeign(['item_class_id']);
            $table->dropColumn('item_class_id');
        });
    }
};
