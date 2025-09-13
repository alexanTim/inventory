<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDelivery extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'dr_number',
        'delivery_date',
        'notes'
    ];

    public function items() {
        return $this->hasMany(PurchaseOrderDeliveryItem::class, 'delivery_id');
    }

    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public static function generateNewDONumber()
    {   
        return 'DR-' . now()->format('ymd') . '-' . self::latest('id')->value('id') + 1; 
    }

    public function supplyBatches()
    {
        return $this->hasMany(SupplyBatch::class, 'delivery_id');
    }

}
