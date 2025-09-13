<x-slot:header>Create Request Slip</x-slot:header>
<x-slot:subheader>Create a new request slip for pet goods distribution</x-slot:subheader>

<div class="pt-4">
    <!-- Back Button -->
    <div class="mb-6">
        <x-button type="button" variant="secondary" href="{{ route('requisition.requestslip') }}" wire:navigate>
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Request Slips
        </x-button>
    </div>

    <!-- Flash Messages -->
    <x-flash-message />

    <!-- Create Request Slip Form -->
    @can(\App\Enums\Enum\PermissionEnum::CREATE_REQUEST_SLIP->value)
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm p-6">
        <form wire:submit.prevent="create">
            <!-- Purpose Selection -->
            <div class="mb-6">
                <x-dropdown 
                    wire:model="purpose" 
                    name="purpose" 
                    label="Purpose" 
                    :options="$purposes"
                    placeholder="Select a Purpose" 
                />
            </div>

            <!-- Department Information -->
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <x-input 
                        type="text" 
                        wire:model="sent_from" 
                        name="sent_from" 
                        label="Sender Department" 
                        readonly 
                    />
                </div>
                <div>
                    <x-dropdown 
                        name="sent_to" 
                        wire:model="sent_to" 
                        label="Receiver Department" 
                        :options="$this->departments->pluck('name', 'id')->toArray()" 
                        placeholder="Select Receiver Department"
                    />
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <x-input 
                    type="textarea" 
                    wire:model="description" 
                    rows="6" 
                    name="description" 
                    label="Description" 
                    placeholder="Please provide a detailed description of the items being requested..."
                />
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-200 dark:border-zinc-600">
                <x-button 
                    type="button" 
                    variant="secondary" 
                    href="{{ route('requisition.requestslip') }}" 
                    wire:navigate
                >
                    Cancel
                </x-button>
                <x-button type="submit" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Request Slip
                </x-button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-red-700 dark:text-red-300 font-medium">You don't have permission to create request slips.</span>
        </div>
    </div>
    @endcan
</div> 