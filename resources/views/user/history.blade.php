<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full mt-4">
                        <thead>
                            <tr>
                                <th class="py-2">ID</th>
                                <th class="py-2">Total Price</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Created At</th>
                                <th class="py-2">Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td class="py-2">{{ $transaction->id }}</td>
                                <td class="py-2">{{ number_format($transaction->total_price, 2) }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded {{ $transaction->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="py-2">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                <td class="py-2">
                                    <details>
                                        <summary class="cursor-pointer">View Items</summary>
                                        <ul class="mt-2">
                                            @foreach(json_decode($transaction->items_json, true) as $item)
                                            <li>{{ $item['name'] }} x{{ $item['quantity'] }} @ ${{ number_format($item['price'], 2) }}</li>
                                            @endforeach
                                        </ul>
                                    </details>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
