<?php

namespace App\Livewire\Pages\Finance;

use Livewire\Component;
use App\Models\Finance;

class Expenses extends Component
{
    public $reference_id;
    public $party;
    public $date;
    public $category;
    public $amount;
    public $payment_method;
    public $status = 'pending';
    public $remarks;
    public $search = '';
    public $perPage = 10;
    public $editingExpenseId = null;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $expenseToDelete = null;

    protected $rules = [
        'reference_id' => 'nullable|string|max:255',
        'party' => 'nullable|string|max:255',
        'date' => 'required|date',
        'category' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'remarks' => 'nullable|string',
    ];

    public function mount()
    {
        $this->generateReferenceId();
    }

    private function generateReferenceId()
    {
        $date = now()->format('ymd'); // e.g. 250721
        $prefix = 'EXP' . $date;

        // Find the latest reference ID for expenses for today only
        $latest = Finance::where('reference_id', 'like', $prefix . '%')
                        ->orderByDesc('reference_id')
                        ->first();

        if ($latest) {
            // Extract the last 3-digit sequence
            $lastNumber = (int) substr($latest->reference_id, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        $this->reference_id = $prefix . $nextNumber;
    }

    public function save()
    {
        $this->validate();
        \App\Models\Finance::create([
            'type' => 'expense',
            'reference_id' => $this->reference_id,
            'party' => $this->party,
            'date' => $this->date,
            'category' => $this->category,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'status' => 'pending',
            'remarks' => $this->remarks,
        ]);
        session()->flash('success', 'Expense saved successfully!');
        $this->reset(['party', 'date', 'category', 'amount', 'payment_method', 'remarks']);
        $this->status = 'pending';
        $this->generateReferenceId();
    }

    public function edit($id)
    {
        $expense = \App\Models\Finance::findOrFail($id);
        $this->editingExpenseId = $expense->id;
        $this->type = 'expense';
        $this->reference_id = $expense->reference_id;
        $this->party = $expense->party;
        $this->date = $expense->date;
        $this->category = $expense->category;
        $this->amount = $expense->amount;
        $this->payment_method = $expense->payment_method;
        $this->status = $expense->status;
        $this->remarks = $expense->remarks;
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate();
        $expense = \App\Models\Finance::findOrFail($this->editingExpenseId);
        $expense->update([
            'type' => 'expense',
            'reference_id' => $this->reference_id,
            'party' => $this->party,
            'date' => $this->date,
            'category' => $this->category,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'remarks' => $this->remarks,
        ]);
        session()->flash('success', 'Expense updated successfully!');
        $this->resetEditState();
        $this->generateReferenceId();
    }

    public function cancel()
    {
        $this->resetEditState();
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->expenseToDelete = null;
    }

    private function resetEditState()
    {
        $this->editingExpenseId = null;
        $this->showEditModal = false;
        $this->reset(['party', 'date', 'category', 'amount', 'payment_method', 'remarks']);
        $this->status = 'pending';
        $this->generateReferenceId();
    }

    public function confirmDelete($id)
    {
        $this->expenseToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $expense = \App\Models\Finance::findOrFail($this->expenseToDelete);
        $expense->delete();
        session()->flash('success', 'Expense deleted successfully!');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $expenses = \App\Models\Finance::query()
            ->where('type', 'expense')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('type', 'like', "%{$this->search}%")
                        ->orWhere('reference_id', 'like', "%{$this->search}%")
                        ->orWhere('party', 'like', "%{$this->search}%")
                        ->orWhere('payment_method', 'like', "%{$this->search}%")
                        ->orWhere('status', 'like', "%{$this->search}%")
                        ->orWhere('remarks', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('date')
            ->paginate($this->perPage);

        return view('livewire.pages.finance.expenses', [
            'expenses' => $expenses,
        ]);
    }
} 