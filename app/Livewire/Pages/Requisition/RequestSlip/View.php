<?php

namespace App\Livewire\Pages\Requisition\RequestSlip;

use App\Enums\Enum\PermissionEnum;
use App\Enums\RolesEnum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\RequestSlip;
use Illuminate\Support\Facades\Auth;

#[
    Layout('components.layouts.app'),
    Title('View Request Slip')
]
class View extends Component
{
    public $request_slip_id;
    public $request_slip;

    public function mount($request_slip_id)
    {
        $this->request_slip_id = $request_slip_id;
        $this->request_slip = RequestSlip::with(['requestedBy', 'approver', 'sentFrom', 'sentTo'])->findOrFail($request_slip_id);
    }

    public function ApproveRequestSlip()
    {
        if (!Auth::user()->can(PermissionEnum::APPROVE_REQUEST_SLIP->value)) {
            session()->flash('error', 'You do not have permission to approve this request slip.');
            return;
        }

        if ($this->request_slip->status !== 'pending') {
            session()->flash('error', 'This request slip cannot be approved as it is not in pending status.');
            return;
        }

        try {
            $this->request_slip->update([
                'status' => 'approved',
                'approver' => Auth::user()->id,
            ]);

            session()->flash('success', 'Request Slip approved successfully.');
            return redirect()->route('requisition.requestslip');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to approve request slip. Please try again.');
        }
    }

    public function RejectRequestSlip()
    {
        if (!Auth::user()->can(PermissionEnum::APPROVE_REQUEST_SLIP->value)) {
            session()->flash('error', 'You do not have permission to reject this request slip.');
            return;
        }

        if ($this->request_slip->status !== 'pending') {
            session()->flash('error', 'This request slip cannot be rejected as it is not in pending status.');
            return;
        }

        try {
            $this->request_slip->update([
                'status' => 'rejected',
                'approver' => Auth::user()->id,
            ]);

            session()->flash('success', 'Request Slip rejected successfully.');
            return redirect()->route('requisition.requestslip');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject request slip. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.pages.requisition.request-slip.view', [
            'request_slip' => $this->request_slip,
        ]);
    }
}
