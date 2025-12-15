<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pilih Metode Pembayaran') }}
            </h2>
            <a href="{{ route('user.cart') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Cart
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Order Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>
                    <div class="space-y-3">
                        @foreach($items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <span class="font-medium">{{ $item['name'] }}</span>
                                <span class="text-gray-500 ml-2">x{{ $item['quantity'] }}</span>
                            </div>
                            <span class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="flex justify-between items-center pt-4 text-xl font-bold">
                            <span>Total:</span>
                            <span class="text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6">Pilih Metode Pembayaran</h3>

                    <form action="{{ route('user.payment.process') }}" method="POST" id="payment-form">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cash Payment -->
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors"
                                 data-method="cash">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Tunai (Cash)</h4>
                                        <p class="text-sm text-gray-500">Bayar langsung dengan uang tunai</p>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="cash" class="hidden payment-input">
                            </div>

                            <!-- Card Payment -->
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors"
                                 data-method="card">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Kartu (Card)</h4>
                                        <p class="text-sm text-gray-500">Bayar menggunakan kartu debit/kredit</p>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="card" class="hidden payment-input">
                            </div>

                            <!-- QRIS Payment -->
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors"
                                 data-method="qris">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.581c1.559 0 2.829 1.265 2.829 2.828 0 .892-.361 1.694-.95 2.272l-1.879 1.88M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">QRIS</h4>
                                        <p class="text-sm text-gray-500">Scan QR code untuk pembayaran</p>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="qris" class="hidden payment-input">
                            </div>

                            <!-- Bank Transfer -->
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition-colors"
                                 data-method="transfer">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Transfer Bank</h4>
                                        <p class="text-sm text-gray-500">Transfer melalui rekening bank</p>
                                    </div>
                                </div>
                                <input type="radio" name="payment_method" value="transfer" class="hidden payment-input">
                            </div>
                        </div>

                        <!-- Process Payment Button -->
                        <div class="mt-8 flex justify-center">
                            <button type="submit" id="process-payment-btn"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Proses Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment method selection
        const paymentCards = document.querySelectorAll('.payment-method-card');
        const processBtn = document.getElementById('process-payment-btn');
        let selectedMethod = null;

        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                paymentCards.forEach(c => {
                    c.classList.remove('border-blue-500', 'bg-blue-50');
                    c.classList.add('border-gray-200');
                });

                // Add selected class to clicked card
                this.classList.remove('border-gray-200');
                this.classList.add('border-blue-500', 'bg-blue-50');

                // Update selected method
                selectedMethod = this.dataset.method;
                const radioInput = this.querySelector('.payment-input');
                radioInput.checked = true;

                // Enable process button
                processBtn.disabled = false;
                processBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            });
        });

        // Form validation
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            if (!selectedMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return false;
            }
        });
    </script>
</x-app-layout>
