<x-slot:header>View Request Slip</x-slot:header>
<x-slot:subheader>Review and manage request slip details</x-slot:subheader>

<div>
    <!-- Request Slip Details Card -->
    <div class="bg-white dark:bg-zinc-800 shadow-md sm:rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                Request Slip #{{ $request_slip->id }}
            </h3>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                Created on {{ $request_slip->created_at->format('F d, Y \a\t g:i A') }}
            </p>
        </div>
        
        <div class="p-6">
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Purpose -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Purpose
                    </label>
                    <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2.5 text-sm text-zinc-900 dark:text-white">
                        {{ $request_slip->purpose }}
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Status
                    </label>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        @if ($request_slip->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                        @elseif ($request_slip->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @elseif ($request_slip->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                        @endif">
                        {{ ucfirst($request_slip->status) }}
                    </div>
                </div>

                <!-- Sent From -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Sent From
                    </label>
                    <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2.5 text-sm text-zinc-900 dark:text-white">
                        {{ $request_slip->sentFrom->name ?? 'N/A' }}
                    </div>
                </div>

                <!-- Sent To -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Sent To
                    </label>
                    <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2.5 text-sm text-zinc-900 dark:text-white">
                        {{ $request_slip->sentTo->name ?? 'N/A' }}
                    </div>
                </div>

                <!-- Requested By -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        Requested By
                    </label>
                    <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2.5 text-sm text-zinc-900 dark:text-white">
                        {{ $request_slip->requestedBy->name ?? 'N/A' }}
                    </div>
                </div>

                <!-- Approved/Rejected By -->
                @if($request_slip->approver)
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                        {{ ucfirst($request_slip->status) }} By
                    </label>
                    <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-2.5 text-sm text-zinc-900 dark:text-white">
                        {{ $request_slip->approver->name ?? 'N/A' }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Description
                </label>
                <div class="bg-zinc-50 dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg px-3 py-3 text-sm text-zinc-900 dark:text-white min-h-[120px] whitespace-pre-wrap">
                    {{ $request_slip->description ?: 'No description provided' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <div>
            <x-button type="button" variant="secondary" href="{{ route('requisition.requestslip') }}" wire:navigate>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </x-button>
        </div>

        @php
            use App\Enums\Enum\PermissionEnum;
        @endphp

        @can(PermissionEnum::APPROVE_REQUEST_SLIP->value)
            @if($request_slip->status === 'pending')
            <div class="flex space-x-3">
                <x-button 
                    type="button" 
                    variant="success" 
                    wire:click="ApproveRequestSlip"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Request
                </x-button>
                
                <x-button 
                    type="button" 
                    variant="danger" 
                    wire:click="RejectRequestSlip"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Request
                </x-button>
            </div>
            @else
            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                Request has been {{ $request_slip->status }}
            </div>
            @endif
        @endcan
    </div>
</div>
