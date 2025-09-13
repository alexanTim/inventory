<?php
namespace App\Livewire\Pages\Supplies\PurchaseOrder;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrderDeliveryItem;
use App\Models\PurchaseOrderDelivery;
use App\Models\PurchaseOrder;

class ViewDeliveryOrderitems extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];
    public $editValue =null;
    public $items = [];
    public $id = null;
    public function updatingSearch()
    {
        $this->resetPage();
    }
 
    public function mount($Id){       
        $this->id = $Id;
    }

    public function render()
    {

        $deliveryItems = PurchaseOrderDelivery::with([
                'supplyBatches.supplyOrder.supplyProfile',
                'purchaseOrder'
            ])->find($this->id);

        \Log::info($deliveryItems);

        return view('livewire.pages.supplies.purchase-order.show-purchase-delivery-items', [
            'deliveries' => $deliveryItems,         
            'purchaseOrders' => PurchaseOrder::pluck('po_num')->toArray(),
        ]);
    }
}
