<?php

namespace App\Livewire\Pages\Customer;

use Livewire\Component;
use App\Models\Customer;

class Index extends Component
{

    public $name, $address, $contact_num, $tin_num;
    public $edit_name, $edit_address, $edit_contact_num, $edit_tin_num;
    public $perPage = 10;
    public $search = '';
    public $showDeleteModal = false;
    public $showEditModal = false;
    public $deleteId = null;
    public $selectedItemId;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact_num' => 'required|string',
            'tin_num' => 'required|string'
        ]);

        Customer::create([
            'name' => $this->name,
            'address' => $this->address,
            'contact_num' => $this->contact_num,
            'tin_num' => $this->tin_num
        ]);

        session()->flash('message', 'Customer Profile Added Successfully.');
        $this->reset(['name', 'address', 'contact_num', 'tin_num']);
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        $this->selectedItemId = $id;
        $this->edit_name = $customer->name;
        $this->edit_address = $customer->address;
        $this->edit_contact_num = $customer->contact_num;
        $this->edit_tin_num = $customer->tin_num;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'edit_name' => 'required|string',
            'edit_address' => 'required|string',
            'edit_contact_num' => 'required|string',
            'edit_tin_num' => 'required|string'
        ]);

        $customer = Customer::findOrFail($this->selectedItemId);
        $customer->update([
            'name' => $this->edit_name,
            'address' => $this->edit_address,
            'contact_num' => $this->edit_contact_num,
            'tin_num' => $this->edit_tin_num
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Customer Profile Updated Successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Customer::findOrFail($this->deleteId)->delete();
        session()->flash('message', 'Customer Profile Deleted Successfully.');
        $this->cancel();
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->reset([
            'showDeleteModal',
            'showEditModal',
            'deleteId',
            'selectedItemId',
            'edit_name',
            'edit_address',
            'edit_contact_num',
            'edit_tin_num',
        ]);
    }

    public function render()
    {
        $items = Customer::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('address', 'like', '%'.$this->search.'%')
            ->orWhere('contact_num', 'like', '%'.$this->search.'%')
            ->orWhere('tin_num', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.pages.customer.index',compact('items'));
    }
}
