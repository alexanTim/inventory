<x-slot:header>Request Slip</x-slot:header>
<x-slot:subheader>Request Inbound or Outbound</x-slot:subheader>
<div class="w-full max-w-none">
    <div class="w-full">
        <!-- New Request Button -->
        @can(\App\Enums\Enum\PermissionEnum::CREATE_REQUEST_SLIP->value) 
        <div class="flex justify-end mb-4">
            <x-button type="button" variant="primary" href="{{ route('requisition.requestslip.create') }}" wire:navigate>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Request
            </x-button>
        </div>
        @endcan
        <x-flash-message />

        <section class="w-full">
            <div class="w-full">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-zinc-800 relative shadow-md sm:rounded-lg overflow-hidden w-full">
                    <div class="flex items-center justify-between p-4 pr-10 w-full">
                        <div class="flex space-x-6 flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-zinc-500 dark:text-zinc-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    class="block w-80 p-2 ps-10 text-sm text-zinc-900 border border-zinc-300 rounded-lg bg-zinc-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Search Request..." required="">
                            </div>
                            <div class="flex items-center space-x-2">
                                <label class="text-sm font-medium text-zinc-900 dark:text-white">Purpose:</label>
                                <select wire:model.live="purposeFilter"
                                    class="bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Purposes</option>
                                    <option value="Pet Food">Pet Food</option>
                                    <option value="Pet Toys">Pet Toys</option>
                                    <option value="Pet Care">Pet Care</option>
                                    <option value="Pet Health">Pet Health</option>
                                    <option value="Pet Grooming">Pet Grooming</option>
                                    <option value="Pet Bedding">Pet Bedding</option>
                                    <option value="Pet Training">Pet Training</option>
                                    <option value="Pet Safety">Pet Safety</option>
                                    <option value="Office Supplies">Office Supplies</option>
                                    <option value="Packaging">Packaging</option>
                                    <option value="Equipment">Equipment</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-sm text-left rtl:text-right text-zinc-500 dark:text-zinc-400 min-w-full">
                            <thead
                                class="text-sm text-zinc-700 uppercase bg-zinc-50 dark:bg-zinc-700 dark:text-zinc-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Sent From
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Purpose
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Requested Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Requested By
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use App\Enums\Enum\PermissionEnum;
                                @endphp

                                @forelse ($request_slips as $request_slip)
                                    <tr wire:key
                                        class="@if($loop->odd) bg-white dark:bg-zinc-900 @else bg-zinc-50 dark:bg-zinc-800 @endif border-b dark:border-zinc-700 border-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                        <td class="px-6 py-4">
                                            <span
                                                class="
                                                                        px-2 py-1 rounded-full text-white text-xs font-semibold
                                                                        @if ($request_slip->status === 'pending') bg-yellow-500
                                                                        @elseif ($request_slip->status === 'approved') bg-green-600
                                                                        @elseif ($request_slip->status === 'rejected') bg-red-600
                                                                            @else bg-zinc-500 @endif">

                                                {{ ucfirst($request_slip->status) }}
                                            </span>
                                        </td>
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-zinc-900 whitespace-nowrap dark:text-white">
                                            {{ $request_slip->sentFrom->name ?? 'N/A' }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $request_slip->purpose }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ Str::limit($request_slip->description, 25) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $request_slip->created_at }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $request_slip->requestedBy->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                           
                                            <a href="{{ route('requisition.requestslip.view', $request_slip->id) }}"
                                                class="font-medium px-1 text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                          
                                            @can(PermissionEnum::DELETE_REQUEST_SLIP->value)
                                                <a href="#" wire:click.prevent='confirmDelete({{ $request_slip->id }})'
                                                    class="font-medium px-1 text-red-600 dark:text-red-500 hover:underline">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-zinc-500 dark:text-zinc-400">
                                            No request slips found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    <div class="py-4 px-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <!-- Per Page Selection -->
                            <div class="flex items-center space-x-4">
                                <label for="perPage" class="text-sm font-medium text-zinc-900 dark:text-white">Per
                                    Page</label>
                                <select id="perPage" wire:model.live="perPage"
                                    class="bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <!-- Pagination Links -->
                            <div>
                                {{ $request_slips->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    </section>
    <div x-data="{ show: @entangle('showDeleteModal') }" 
         x-show="show" 
         x-cloak 
         @keydown.escape.window="show = false; $wire.cancel()"
         class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-zinc-500 bg-opacity-50 transition-opacity" 
             x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="show = false; $wire.cancel()"></div>
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-lg dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600" @click.stop>
                <button type="button" class="absolute top-3 right-2.5 text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white" wire:click="cancel">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-zinc-400 w-12 h-12 dark:text-zinc-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-zinc-500 dark:text-zinc-400">Are you sure you want to delete this request slip?</h3>
                    <div class="flex justify-center space-x-2">
                        <button type="button" wire:click="delete" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                            Yes, I'm sure
                        </button>
                        <button type="button" wire:click="cancel" class="text-zinc-900 bg-white hover:bg-zinc-100 focus:ring-4 focus:outline-none focus:ring-zinc-300 dark:focus:ring-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-600 dark:hover:text-white dark:hover:bg-zinc-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center border border-zinc-200 transition-colors duration-200">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>
