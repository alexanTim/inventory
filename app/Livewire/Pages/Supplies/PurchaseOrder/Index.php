<?php

namespace App\Livewire\Pages\Supplies\PurchaseOrder;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $deletingPurchaseOrderId = null;
    public $showDeleteModal = false;

    // QR Code Modal properties
    public $showQrModal = false;
    public $selectedPurchaseOrder = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    // Computed properties for QR modal totals
    public function getSelectedPurchaseOrderTotalQuantityProperty()
    {
        if (!$this->selectedPurchaseOrder) {
            return 0;
        }
        return $this->selectedPurchaseOrder->supplyOrders->sum('order_qty');
    }

    public function getSelectedPurchaseOrderTotalPriceProperty()
    {
        if (!$this->selectedPurchaseOrder) {
            return 0;
        }
        return $this->selectedPurchaseOrder->supplyOrders->sum('order_total_price');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deletingPurchaseOrderId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            DB::beginTransaction();

            $purchaseOrder = PurchaseOrder::with(['supplyOrders', 'supplier'])->findOrFail($this->deletingPurchaseOrderId);
            
            // Prepare item summary for activity log
            $itemSummary = $purchaseOrder->supplyOrders->map(function($order) {
                $supply = $order->supplyProfile;
                return 'SKU: ' . ($supply->supply_sku ?? '-') . ', Desc: ' . ($supply->supply_description ?? '-') . ', Qty: ' . $order->order_qty . ', Unit Price: ' . $order->unit_price;
            })->implode('<br>');

            // Log activity before deletion
            activity()
                ->causedBy(\Illuminate\Support\Facades\Auth::user())
                ->performedOn($purchaseOrder)
                ->withProperties([
                    'po_num' => $purchaseOrder->po_num,
                    'supplier' => $purchaseOrder->supplier?->name ?? 'N/A',
                    'total_price' => $purchaseOrder->total_price,
                    'items' => $itemSummary,
                ])
                ->log('Purchase order deleted');
            
            // Delete all associated supply orders first
            $purchaseOrder->supplyOrders()->delete();

            // Delete the purchase order
            $purchaseOrder->delete();

            DB::commit();

            $this->reset(['deletingPurchaseOrderId', 'showDeleteModal']);
            session()->flash('message', 'Purchase order and its associated items have been deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete purchase order: ' . $e->getMessage());
        }
    }

    public function showQrCode($id)
    {
        $this->selectedPurchaseOrder = PurchaseOrder::with([
            'supplier', 
            'department', 
            'orderedBy', 
            'approverInfo',
            'supplyOrders.supplyProfile'
        ])->findOrFail($id);
        $this->showQrModal = true;
    }

    public function generateQrCodeData($purchaseOrder)
    {
        // Create comprehensive PO data for QR code scanning
        $qrData = [
            'type' => 'purchase_order',
            'po_num' => $purchaseOrder->po_num,
            'status' => $purchaseOrder->status,
            'supplier' => $purchaseOrder->supplier ? $purchaseOrder->supplier->name : 'N/A',
            'department' => $purchaseOrder->department ? $purchaseOrder->department->name : 'N/A',
            'total_qty' => $purchaseOrder->total_qty,
            'total_price' => $purchaseOrder->total_price,
            'order_date' => $purchaseOrder->order_date->format('Y-m-d'),
            'system' => 'Gentle Walker PO System'
        ];

        return json_encode($qrData);
    }

    public function closeQrModal()
    {
        $this->showQrModal = false;
        $this->selectedPurchaseOrder = null;
        $this->dispatch('modal-closed');
    }

    public function cancel()
    {
        $this->reset(['deletingPurchaseOrderId', 'showDeleteModal']);
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::query()
            ->where('po_type', 'supply')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('del_to', 'like', '%' . $this->search . '%')
                        ->orWhere('payment_terms', 'like', '%' . $this->search . '%')
                        ->orWhere('quotation', 'like', '%' . $this->search . '%')
                        ->orWhere('po_num', 'like', '%' . $this->search . '%')
                        ->orWhereHas('supplier', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->with(['supplier', 'department'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.pages.supplies.purchase-order.index', [
            'purchaseOrders' => $purchaseOrders
        ]);
    }
}
