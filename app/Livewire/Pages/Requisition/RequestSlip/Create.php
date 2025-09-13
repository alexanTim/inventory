<?php

namespace App\Livewire\Pages\Requisition\RequestSlip;

use App\Models\Department;
use App\Enums\Enum\PermissionEnum;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\RequestSlip;
use Livewire\Attributes\Validate;

#[
    Layout('components.layouts.app'),
    Title('Create Request Slip')
]
class Create extends Component
{
    #[Validate('required|in:Pet Food,Pet Toys,Pet Care,Pet Health,Pet Grooming,Pet Bedding,Pet Training,Pet Safety,Office Supplies,Packaging,Equipment,Other')]
    public $purpose = '';

    #[Validate('required|string|max:255')]
    public $description = '';

    public $sent_from;

    #[Validate('required|exists:departments,id')]
    public $sent_to = '';

    public $requested_by = '';
    public $approver = '';
    public $request_date = '';

    public array $purposes = [
        'Pet Food' => 'Request Pet Food Supplies',
        'Pet Toys' => 'Request Pet Toys & Accessories',
        'Pet Care' => 'Request Pet Care Products',
        'Pet Health' => 'Request Pet Health & Medical Supplies',
        'Pet Grooming' => 'Request Pet Grooming Supplies',
        'Pet Bedding' => 'Request Pet Bedding & Comfort Items',
        'Pet Training' => 'Request Pet Training Supplies',
        'Pet Safety' => 'Request Pet Safety & Security Items',
        'Office Supplies' => 'Request Office Supplies',
        'Packaging' => 'Request Packaging Materials',
        'Equipment' => 'Request Equipment & Tools',
        'Other' => 'Request Other Items',
    ];

    public function mount()
    {
        // Get user's department name safely
        $user = Auth::user();
        $department = $user->department;
        
        if ($department) {
            $this->sent_from = $department->name;
        } else {
            // Fallback if user has no department
            $this->sent_from = 'Unknown Department';
        }

        // Set default purpose
        $this->purpose = array_key_first($this->purposes);
        
        // Set default sent_to to first department
        $this->sent_to = Department::orderBy('name')->first()?->id ?? '';
    }

    #[Computed()]
    public function departments()
    {
        return Department::orderBy('name')->get();
    }

    public function create()
    {
        $this->validate();

        // Get user and check department
        $user = Auth::user();
        $department = $user->department;
        
        if (!$department) {
            session()->flash('error', 'User must be assigned to a department to create request slips.');
            return;
        }

        RequestSlip::create([
            'status' => 'pending',
            'purpose' => $this->purpose,
            'description' => $this->description,
            'sent_from' => $department->id,
            'sent_to' => $this->sent_to,
            'request_date' => now(),
            'requested_by' => $user->id,
        ]);

        session()->flash('message', 'Request created successfully.');
        
        // Redirect back to the request slip list
        return redirect()->route('requisition.requestslip');
    }

    public function render()
    {
        return view('livewire.pages.requisition.request-slip.create');
    }
} 