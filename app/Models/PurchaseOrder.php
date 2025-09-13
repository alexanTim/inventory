<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'del_to',
        'supplier_id',
        'po_num',
        'status',
        'total_price',
        'order_date',
        'del_on',
        'payment_terms',
        'quotation',
        'total_est_weight',
        'po_type',
        'total_qty',
        'ordered_by',
        'approver',
        'loaded_date'
    ];

    protected $casts = [
        'order_date' => 'date',
        'del_on' => 'date',
        'loaded_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplyOrders(): HasMany
    {
        return $this->hasMany(SupplyOrder::class);
    }
    public function rawMatOrders(): HasMany
    {
        return $this->hasMany(RawMatOrder::class);
    }

    public function orderedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    public function approverInfo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'del_to');
    }

    public function deliveries() {
        return $this->hasMany(PurchaseOrderDelivery::class);
    }
}
