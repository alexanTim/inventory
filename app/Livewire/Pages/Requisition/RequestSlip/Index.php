<?php

namespace App\Livewire\Pages\Requisition\RequestSlip;


use App\Enums\Enum\PermissionEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\RequestSlip;
use Livewire\Attributes\On;
use App\Events\RequestSlipCreated;
#[
    Layout('components.layouts.app'),
    Title('Request Slip')
]
class Index extends Component
{

    use WithPagination;


    public bool $showDeleteModal = false;
    public ?int $deletingRequestSlipId = null;
    #[Url(as: 'q')]
    public $search = '';
    public int $perPage = 10;



    public $purposeFilter = '';

    public function mount()
    {
        // This method is no longer needed for the index page
    }






    public function confirmDelete(int $id): void
    {
        if (!Gate::allows(PermissionEnum::DELETE_REQUEST_SLIP->value)) {
            abort(403, 'You do not have permission to delete this request slip.');
        }

        $this->deletingRequestSlipId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!Gate::allows(PermissionEnum::DELETE_REQUEST_SLIP->value)) {
            abort(403, 'You do not have permission to delete this request slip.');
        }

        if ($this->deletingRequestSlipId === null) {
            session()->flash('error', 'No request slip selected for deletion.');
            return;
        }

        $requestSlip = RequestSlip::find($this->deletingRequestSlipId);
        if ($requestSlip) {
            $requestSlip->delete();
            session()->flash('message', 'Request Slip deleted successfully.');
        } else {
            session()->flash('error', 'Request Slip not found.');
        }

        $this->cancel();
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->showDeleteModal = false;
        $this->deletingRequestSlipId = null;
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingPurposeFilter()
    {
        $this->resetPage();
    }
    // public function updatingPerPage()
    // {
    //     $this->resetPage();
    // }
    #[On('echo:request-slip,RequestSlipCreated')]
    public function onRequestSlipCreated($event)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = RequestSlip::with(['sentFrom', 'sentTo', 'requestedBy'])->search($this->search);
        
        // Apply purpose filter if selected
        if (!empty($this->purposeFilter)) {
            $query->where('purpose', $this->purposeFilter);
        }
        
        return view('livewire.pages.requisition.request-slip.index', [
            'request_slips' => $query->latest()->paginate($this->perPage),
        ]);
    }
}
