@php
    $fiturConfig = config('fitur');
    $statusFeature = $fiturConfig['status'] ?? true;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Report') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-responsive">
                <div class="p-4 sm:p-6 text-gray-900">
                    @if($transactions->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="hidden md:block">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                                            @if($statusFeature)
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            @endif
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($transactions as $index => $transaction)
                                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $transaction->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                            @if($statusFeature)
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex flex-col">
                                                    <span>{{ $transaction->created_at->format('d/m/Y') }}</span>
                                                    <span class="text-xs text-gray-400">{{ $transaction->created_at->format('H:i') }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <details class="group">
                                                    <summary class="cursor-pointer list-none flex items-center hover:text-blue-600 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                        View {{ count(json_decode($transaction->items_json, true)) }} items
                                                    </summary>
                                                    <div class="mt-3 pl-6 border-l-2 border-gray-200">
                                                        @foreach(json_decode($transaction->items_json, true) as $item)
                                                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                                                            <div class="flex-1">
                                                                <span class="font-medium text-gray-900">{{ $item['name'] }}</span>
                                                                <span class="text-sm text-gray-500 ml-2">x{{ $item['quantity'] }}</span>
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </details>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="md:hidden space-y-4">
                            @foreach($transactions as $transaction)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">#{{ $transaction->id }}</h3>
                                        <p class="text-sm text-gray-500">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Customer: {{ $transaction->user->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                        @if($statusFeature)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                                            {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                               ($transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <details class="mb-3">
                                    <summary class="cursor-pointer text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        View {{ count(json_decode($transaction->items_json, true)) }} items
                                    </summary>
                                    <div class="mt-3 pl-4 border-l-2 border-gray-200 space-y-2">
                                        @foreach(json_decode($transaction->items_json, true) as $item)
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1">
                                                <span class="font-medium text-gray-900">{{ $item['name'] }}</span>
                                                <span class="text-sm text-gray-500 ml-2">x{{ $item['quantity'] }}</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </details>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                            <p class="mt-1 text-sm text-gray-500">There are no transactions to display at this time.</p>
                        </div>
                    @endif

                    @if($transactions->hasPages())
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

   
</x-app-layout>
