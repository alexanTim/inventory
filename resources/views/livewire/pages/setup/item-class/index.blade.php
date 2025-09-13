<x-slot:header>Item Classes</x-slot:header>
<x-slot:subheader>Item Class Management</x-slot:subheader>
<div class="">
    <div class="">        
        @can(\App\Enums\Enum\PermissionEnum::CREATE_ITEM_CLASSES->value)
        <x-collapsible-card title="Create Item Class" open="false" size="full">
            <form wire:submit.prevent="create" x-show="open" x-transition>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <x-input type="text" wire:model="name" name="name" label="Item Class Name" placeholder="Enter item class name" />
                    </div>
                    <div>
                        <x-input type="text" wire:model="description" name="description" label="Description" placeholder="Enter description" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button type="submit" variant="primary">Submit</x-button>
                </div>
            </form>
        </x-collapsible-card>
        @endcan
        @if (session('message'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 4000)"
                class="transition duration-500 ease-in-out"
                x-transition
            >
                <x-flash-message />
            </div>
        @endif

        <x-collapsible-card title="Item Classes List" open="true" size="full">
            <div class="flex items-center justify-between p-4 pr-10">
                <div class="flex space-x-6">
                    <div class="relative">
                        <x-input type="text" wire:model.live="search" name="search" label="Search" placeholder="Search item classes..." />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400">
                    <thead class="text-sm text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Item Class Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itemClasses as $itemClass)
                        <tr class="odd:bg-white odd:dark:bg-zinc-900 even:bg-zinc-50 even:dark:bg-zinc-800 border-b dark:border-zinc-700 border-zinc-200">
                            <td class="px-6 py-4">
                                {{ $itemClass->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $itemClass->description ?: 'No Description' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    @can(\App\Enums\Enum\PermissionEnum::EDIT_ITEM_CLASSES->value)
                                    <x-button type="button" wire:click="edit({{ $itemClass->id }})" variant="warning" size="sm">Edit</x-button>
                                    @endcan
                                    @can(\App\Enums\Enum\PermissionEnum::DELETE_ITEM_CLASSES->value)
                                    <x-button type="button" wire:click="confirmDelete({{ $itemClass->id }})" variant="danger" size="sm">Delete</x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-data="{ show: @entangle('showEditModal') }" x-show="show" x-cloak class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-zinc-600">
                            <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                Edit Item Class
                            </h3>
                            <button type="button" class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white" wire:click="cancel">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <x-input type="text" wire:model="name" name="name" label="Item Class Name" placeholder="Enter item class name" />
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <x-input type="text" wire:model="description" name="description" label="Description" placeholder="Enter description" />
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center p-6 space-x-2 border-t border-zinc-200 rounded-b dark:border-zinc-600">
                            <x-button type="button" wire:click="{{ $editingItemClassId ? 'update' : 'create' }}" variant="primary">{{ $editingItemClassId ? 'Save changes' : 'Submit' }}</x-button>
                            <x-button type="button" wire:click="cancel" variant="secondary">Cancel</x-button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ show: @entangle('showDeleteModal') }" x-show="show" x-cloak class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
                <div class="relative w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <button type="button" class="absolute top-3 right-2.5 text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white" wire:click="cancel">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-6 text-center">
                            <svg class="mx-auto mb-4 text-zinc-400 w-12 h-12 dark:text-zinc-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-zinc-500 dark:text-zinc-400">Are you sure you want to delete this item class?</h3>
                            <div class="flex justify-center space-x-2">
                                <x-button type="button" wire:click="delete" variant="danger">Yes, I'm sure</x-button>
                                <x-button type="button" wire:click="cancel" variant="secondary">No, cancel</x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-4 px-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="text-sm font-medium text-zinc-900 dark:text-white">Per Page:</label>
                        <x-dropdown wire:model.live="perPage" name="perPage" :options="[
                            '5' => '5',
                            '10' => '10',
                            '25' => '25',
                            '50' => '50',
                            '100' => '100'
                        ]" />
                    </div>
                    <div>
                        {{ $itemClasses->links() }}
                    </div>
                </div>
            </div>
        </x-collapsible-card>
    </div>
</div>
