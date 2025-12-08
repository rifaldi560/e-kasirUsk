<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3>Point of Sale</h3>
                    <a href="{{ route('user.pos') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Go to POS</a>
                    <br><br>
                    <h3>Order History</h3>
                    <a href="{{ route('user.history') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View History</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
