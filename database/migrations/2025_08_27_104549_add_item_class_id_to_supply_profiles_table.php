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
        Schema::table('supply_profiles', function (Blueprint $table) {
            $table->foreignId('item_class_id')->nullable()->constrained('item_classes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_profiles', function (Blueprint $table) {
            $table->dropForeign(['item_class_id']);
            $table->dropColumn('item_class_id');
        });
    }
};
