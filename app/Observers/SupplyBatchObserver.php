<?php

namespace App\Observers;

use App\Models\SupplyBatch;

class SupplyBatchObserver
{
    /**
     * Handle the SupplyBatch "created" event.
     */
    public function created(SupplyBatch $supplyBatch): void
    {
        //
    }

    /**
     * Handle the SupplyBatch "updated" event.
     */
    public function updated(SupplyBatch $supplyBatch): void
    {
        //
    }

    /**
     * Handle the SupplyBatch "deleted" event.
     */
    public function deleted(SupplyBatch $supplyBatch): void
    {
        //
    }

    /**
     * Handle the SupplyBatch "restored" event.
     */
    public function restored(SupplyBatch $supplyBatch): void
    {
        //
    }

    /**
     * Handle the SupplyBatch "force deleted" event.
     */
    public function forceDeleted(SupplyBatch $supplyBatch): void
    {
        //
    }
}
