<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice Pembayaran') }}
            </h2>
            <div class="flex space-x-2">
                {{-- <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Kembali ke POS
                </a> --}}
                <a href="{{ route('user.receipt.pdf', $transaction) }}" target="_blank" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Download PDF
                </a>
                {{-- <button onclick="window.print()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Print Invoice
                </button>
            </div> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            {{-- @if(session('auto_download_pdf'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                    PDF invoice sedang diunduh secara otomatis...
                </div>
            @endif --}}

            <!-- Invoice -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Invoice Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-8 py-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">INVOICE</h1>
                            <p class="text-blue-100">Nomor Invoice: #{{ $transaction->id }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold">E-KASIR USK</div>
                            <div class="text-blue-100 text-sm mt-1">
                                Jl. Contoh No. 123, Banda Aceh<br>
                                Telp: (0651) 123456
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="px-8 py-6 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pembeli</h3>
                            <div class="bg-white p-4 rounded-lg border">
                                <p class="text-gray-600"><span class="font-medium">Nama:</span> {{ Auth::user()->name }}</p>
                                <p class="text-gray-600"><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                                <p class="text-gray-600"><span class="font-medium">No. Antrian:</span> {{ $transaction->queue_number }}</p>
                            </div>
                        </div>

                        <!-- Invoice Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Detail Invoice</h3>
                            <div class="bg-white p-4 rounded-lg border space-y-2">
                                <p class="text-gray-600"><span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($transaction->queue_date)->format('d F Y') }}</p>
                                <p class="text-gray-600"><span class="font-medium">Waktu:</span> {{ $transaction->created_at->format('H:i:s') }}</p>
                                <p class="text-gray-600"><span class="font-medium">Kasir:</span> {{ Auth::user()->name }}</p>
                                <p class="text-gray-600"><span class="font-medium">Status:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="px-8 py-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Pembelian</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white border border-gray-300 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(json_decode($transaction->items_json, true) as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total and Payment Info -->
                <div class="px-8 py-6 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pembayaran</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600 mb-1"><span class="font-medium">Metode Pembayaran:</span></p>
                                    <p class="text-gray-800 font-medium">
                                        @switch($transaction->payment_method)
                                            @case('cash')
                                                Tunai (Cash)
                                                @break
                                            @case('card')
                                                Kartu Debit/Kredit
                                                @break
                                            @case('qris')
                                                QRIS
                                                @break
                                            @case('transfer')
                                                Transfer Bank
                                                @break
                                            @default
                                                {{ ucfirst($transaction->payment_method) }}
                                        @endswitch
                                    </p>
                                </div>
                                <div class="text-right md:text-left">
                                    <p class="text-2xl font-bold text-gray-800">Total Pembayaran</p>
                                    <p class="text-3xl font-black text-green-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-8 py-6 bg-white border-t">
                    <div class="text-center">
                        <p class="text-gray-600 mb-2">Terima kasih atas kunjungan Anda ke E-KASIR USK</p>
                        <p class="text-sm text-gray-500">Barang yang sudah dibeli tidak dapat dikembalikan kecuali ada kesalahan dari pihak kami</p>
                        <p class="text-xs text-gray-400 mt-4">Invoice ini dibuat secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.history') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-center transition-colors">
                    Lihat Riwayat
                </a>
                <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-center transition-colors">
                    Belanja Lagi
                </a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-4xl, .max-w-4xl * {
                visibility: visible;
            }
            .max-w-4xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .flex.space-x-2,
            .mt-8 {
                display: none !important;
            }
        }
    </style>

    {{-- <script>
        // Auto-refresh queue number (in case of system updates)
        setInterval(function() {
            // You could add AJAX call here to check for queue updates
            // For now, we'll just keep the static display
        }, 30000); // Check every 30 seconds

        // Print functionality
        function printReceipt() {
            window.print();
        }

        // Auto download PDF after payment processing
        @if(session('auto_download_pdf'))
        window.addEventListener('load', function() {
            // Trigger PDF download automatically
            var link = document.createElement('a');
            link.href = '{{ route("user.receipt.pdf", $transaction) }}';
            link.target = '_blank';
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
        @endif
    </script> --}}
</x-app-layout>
