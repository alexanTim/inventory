<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDeliveryItem extends Model
{  
     protected $fillable = [
        'delivery_id',
        'supply_order_id',
        'received_qty'
    ];

     // Link to the PO item (SupplyOrder)
    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

      /**
     * Link to the parent PurchaseOrderDelivery
     */
    public function purchaseOrderDelivery()
    {
        return $this->belongsTo(PurchaseOrderDelivery::class, 'delivery_id');
    }
}
