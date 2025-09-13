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
        Schema::table('supply_batches', function (Blueprint $table) {
            //                   
            if (!Schema::hasColumn('supply_batches', 'received_qty')) {
                $table->integer('received_qty')->nullable()->after('current_qty');
            }

            $table->unsignedBigInteger('delivery_id')->nullable();

             $table->foreign('delivery_id')
              ->references('id')
              ->on('purchase_order_deliveries')
              ->onDelete('cascade'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_batches', function (Blueprint $table) {
            //
            $table->dropColumn(['delivery_id', 
                'received_qty'
            ]);
        });
    }
};
