<?php

namespace App\Livewire\Pages\Supplies\Inventory;

use App\Models\SupplyProfile;
use App\Models\ItemType;
use App\Models\ItemClass;
use App\Models\Allocation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    // Filter properties
    public $itemClassFilter = '';
    public $itemTypeFilter = '';
    public $allocationFilter = '';
    public $lowStockFilter = '';

    // Form properties
    #[Validate('required|exists:item_classes,id')]
    public $item_class_id = '';

    #[Validate('required|exists:item_types,id')]
    public $item_type_id = '';

    #[Validate('required|exists:allocations,id')]
    public $allocation_id = '';

    #[Validate('required|string|max:255')]
    public $supply_description = '';

    #[Validate('required|string|max:255')]
    public $supply_uom = '';

    // Current quantity is always 0 for new product profiles
    public $supply_qty = 0;

    #[Validate('required|numeric|min:0')]
    public $low_stock_threshold_percentage = 20;

    #[Validate('required|numeric|min:0')]
    public $unit_cost = '';

    #[Validate('required|numeric|min:0')]
    public $supply_price1 = '';
    #[Validate('required|numeric|min:0')]
    public $supply_price2 = '';
    #[Validate('required|numeric|min:0')]
    public $supply_price3 = '';
    #[Validate('required|string|max:255')]
    public $supply_sku = '';

    public int $perPage = 10;

    // Edit property
    public $editingSupplyId = null;

    // Delete property
    public $deletingSupplyId = null;

    // QR Code Modal properties
    public $showQrModal = false;
    public $selectedSupply = null;

    // Modal states
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $itemTypes;
    public $itemClasses;
    public $allocations;

    public function mount()
    {
        $this->itemTypes = ItemType::all();
        $this->itemClasses = ItemClass::all();
        $this->allocations = Allocation::all();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedItemClassFilter()
    {
        $this->resetPage();
        // Ensure QR modal is closed when filters change
        $this->showQrModal = false;
        $this->selectedSupply = null;
    }

    public function updatedItemTypeFilter()
    {
        $this->resetPage();
        // Ensure QR modal is closed when filters change
        $this->showQrModal = false;
        $this->selectedSupply = null;
    }

    public function updatedAllocationFilter()
    {
        $this->resetPage();
        // Ensure QR modal is closed when filters change
        $this->showQrModal = false;
        $this->selectedSupply = null;
    }

    public function updatedLowStockFilter()
    {
        $this->resetPage();
        // Ensure QR modal is closed when filters change
        $this->showQrModal = false;
        $this->selectedSupply = null;
    }

    public function create()
    {
        $this->validate();

        SupplyProfile::create([
            'supply_sku' => $this->supply_sku,
            'item_class_id' => $this->item_class_id,
            'item_type_id' => $this->item_type_id,
            'allocation_id' => $this->allocation_id,
            'supply_description' => $this->supply_description,
            'supply_qty' => $this->supply_qty,
            'supply_uom' => $this->supply_uom,
            'low_stock_threshold_percentage' => $this->low_stock_threshold_percentage,
            'unit_cost' => $this->unit_cost,
            'supply_price1' => $this->supply_price1,
            'supply_price2' => $this->supply_price2,
            'supply_price3' => $this->supply_price3,
        ]);

        session()->flash('message', 'Product profile created successfully!');
        $this->resetForm();
    }

    public function edit($id)
    {
        $supply = SupplyProfile::findOrFail($id);
        $this->editingSupplyId = $id;
        $this->item_class_id = $supply->item_class_id;
        $this->item_type_id = $supply->item_type_id;
        $this->allocation_id = $supply->allocation_id;
        $this->supply_description = $supply->supply_description;
        $this->supply_qty = $supply->supply_qty;
        $this->supply_uom = $supply->supply_uom;
        $this->low_stock_threshold_percentage = $supply->low_stock_threshold_percentage;
        $this->unit_cost = $supply->unit_cost;
        $this->supply_price1 = $supply->supply_price1;
        $this->supply_price2 = $supply->supply_price2;
        $this->supply_price3 = $supply->supply_price3;
        $this->supply_sku = $supply->supply_sku;
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();

        $supply = SupplyProfile::findOrFail($this->editingSupplyId);
        $supply->update([
            'item_class_id' => $this->itemClassFilter,
            'item_type_id' => $this->item_type_id,
            'allocation_id' => $this->allocation_id,
            'supply_description' => $this->supply_description,
            'supply_qty' => $this->supply_qty,
            'supply_uom' => $this->supply_uom,
            'low_stock_threshold_percentage' => $this->low_stock_threshold_percentage,
            'unit_cost' => $this->unit_cost,
            'supply_price1' => $this->supply_price1,
            'supply_price2' => $this->supply_price2,
            'supply_price3' => $this->supply_price3,
            'supply_sku' => $this->supply_sku
        ]);

        $this->resetForm();
        session()->flash('message', 'Supply profile updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deletingSupplyId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $supply = SupplyProfile::findOrFail($this->deletingSupplyId);
        $supply->delete();
        
        $this->reset(['deletingSupplyId', 'showDeleteModal']);
        session()->flash('message', 'Supply profile deleted successfully.');
    }

    public function showQrCode($id)
    {
        $this->selectedSupply = SupplyProfile::with(['itemType', 'itemClass', 'allocation'])->findOrFail($id);
        $this->showQrModal = true;
    }



    public function closeQrModal()
    {
        $this->showQrModal = false;
        $this->selectedSupply = null;
        $this->dispatch('modal-closed');
    }

    public function cancel()
    {
        $this->resetForm();
        $this->reset(['showEditModal', 'showDeleteModal', 'showQrModal', 'editingSupplyId', 'deletingSupplyId', 'selectedSupply']);
    }

    protected function resetForm()
    {
        $this->reset([
            'supply_sku',
            'item_class_id',
            'item_type_id',
            'allocation_id',
            'supply_description',
            'supply_qty',
            'supply_uom',
            'low_stock_threshold_percentage',
            'unit_cost',
            'supply_price1',
            'supply_price2',
            'supply_price3',
        ]);
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $supplies = SupplyProfile::query()
            ->with(['itemType', 'itemClass', 'allocation'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('supply_description', 'like', '%' . $this->search . '%')
                        ->orWhere('supply_sku', 'like', '%' . $this->search . '%')
                        ->orWhereHas('itemType', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('itemClass', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('allocation', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->itemClassFilter && $this->itemClassFilter !== '', function ($query) {
                $query->where('item_class_id', $this->itemClassFilter);
            })
            ->when($this->itemTypeFilter && $this->itemTypeFilter !== '', function ($query) {
                $query->where('item_type_id', $this->itemTypeFilter);
            })
            ->when($this->allocationFilter && $this->allocationFilter !== '', function ($query) {
                $query->where('allocation_id', $this->allocationFilter);
            })
            ->when($this->lowStockFilter && $this->lowStockFilter !== '', function ($query) {
                if ($this->lowStockFilter === 'low_stock') {
                    // Filter products that are below their individual low stock threshold
                    // Since we can't easily calculate this in SQL, we'll use a reasonable threshold
                    $query->where('supply_qty', '<=', 10);
                } elseif ($this->lowStockFilter === 'out_of_stock') {
                    $query->where('supply_qty', '<=', 0);
                } elseif ($this->lowStockFilter === 'critical_stock') {
                    $query->where('supply_qty', '<=', 5); // Critical threshold of 5 units
                } elseif ($this->lowStockFilter === 'healthy_stock') {
                    // Filter products with healthy stock levels (11+ units)
                    $query->where('supply_qty', '>', 10);
                }
            })
            ->latest()
            ->paginate($this->perPage);

        // Load batch information for all items
        $supplies->each(function ($supply) {
            // Load all batches for the supply
            $supply->load(['supplyBatches' => function ($query) {
                $query->orderBy('expiration_date', 'asc');
            }]);
            
            // Load active batches for all items (not just consumables)
            $supply->load(['activeBatches' => function ($query) {
                $query->orderBy('expiration_date', 'asc')->limit(5);
            }]);
            
            // Basic batch statistics for all items
            $supply->total_batch_qty = $supply->getTotalBatchQuantity();
            
            // Extended batch statistics for consumable items (expiration tracking)
            if ($supply->isConsumable()) {
                $supply->expired_batches_count = $supply->supplyBatches()->expired()->count();
                $supply->expiring_soon_count = $supply->supplyBatches()->expiringSoon(30)->count();
                $supply->next_expiry = $supply->activeBatches()
                    ->whereNotNull('expiration_date')
                    ->orderBy('expiration_date', 'asc')
                    ->first()?->expiration_date;
            }
        });



        return view('livewire.pages.supplies.inventory.index', [
            'supplies' => $supplies,
            'itemTypes' => $this->itemTypes,
            'itemClasses' => $this->itemClasses,
            'allocations' => $this->allocations,
            'uomOptions' => SupplyProfile::getUnitOfMeasureOptions(),
        ]);
    }
}
