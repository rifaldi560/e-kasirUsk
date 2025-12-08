<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Management') }}
            </h2>
            <a href="{{ route('admin.reports') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Status Filter Tabs -->
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <a href="{{ route('admin.transactions.index') }}" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ request('status') == 'pending' || !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Pending Orders ({{ $pendingCount ?? 0 }})
                                </a>
                                <a href="{{ route('admin.transactions.index', ['status' => 'completed']) }}" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ request('status') == 'completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Completed Orders ({{ $completedCount ?? 0 }})
                                </a>
                            </nav>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $transaction->id }}</div>
                                        <div class="text-sm text-gray-500">Order</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-semibold text-green-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Pending
                                            </span>
                                        @elseif($transaction->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="text-sm">{{ $transaction->created_at->format('M j, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <button onclick="toggleItems({{ $transaction->id }})" class="text-blue-600 hover:text-blue-900 font-medium">
                                                View {{ count(json_decode($transaction->items_json, true)) }} items
                                            </button>
                                            <div id="items-{{ $transaction->id }}" class="hidden mt-2 space-y-1">
                                                @foreach(json_decode($transaction->items_json, true) as $item)
                                                <div class="flex justify-between text-xs bg-gray-50 p-2 rounded">
                                                    <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                                    <span class="font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($transaction->status === 'pending')
                                            <form action="{{ route('admin.transactions.complete', $transaction) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to mark this order as completed?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Complete Order
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500 italic">Order completed</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 mb-1">
                                                @if(request('status') == 'completed')
                                                    No completed orders found
                                                @else
                                                    No pending orders found
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500 mb-4">
                                                @if(request('status') == 'completed')
                                                    Completed orders will appear here
                                                @else
                                                    New orders will appear here when customers place them
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleItems(orderId) {
            const itemsDiv = document.getElementById(`items-${orderId}`);
            itemsDiv.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
