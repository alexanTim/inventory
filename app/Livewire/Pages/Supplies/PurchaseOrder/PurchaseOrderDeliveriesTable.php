<?php
namespace App\Livewire\Pages\Supplies\PurchaseOrder;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrderDelivery;
use App\Models\PurchaseOrder;

class PurchaseOrderDeliveriesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    protected $updatesQueryString = ['search'];
    public $editValue =null;
    public $items = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function submitOrder(){

    }

    public function updatedPoId($value)
    {
        $po = PurchaseOrder::with('supplyOrders.supplyProfile')->find($value);

        $this->items = [];

        if ($po) {
            foreach ($po->supplyOrders as $item) {
                $this->items[] = [
                    'purchase_order_id' => $item->id,
                    'supply_sku' => $item->supply_sku,
                    'expected_qty' => $item->quantity,
                    'received_qty' => 0,
                    'remaining_qty' => $item->quantity - $item->deliveryItems->sum('received_qty'),
                ];
            }
        }
    }

    public function render()
    {
        $deliveries = PurchaseOrderDelivery::with('supplyBatches')->latest()->paginate($this->perPage);             

        \Log::info($deliveries);
        return view('livewire.pages.supplies.purchase-order.purchase-order-deliveries-table', [
            'deliveries' => $deliveries,         
            'purchaseOrders' => PurchaseOrder::pluck('po_num')->toArray(),
        ]);
    }
}
