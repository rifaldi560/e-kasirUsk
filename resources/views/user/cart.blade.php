<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cart') }} (<span id="cart-count-header">0</span>)
            </h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                    Back to POS
                </a>
                <a href="{{ route('user.history') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                    View History
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div id="cart-content">
                        <p class="text-center text-gray-500 py-8">Your cart is managed on the POS page. Please go back to POS to add items to cart.</p>
                        <div class="text-center">
                            <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Go to POS
                            </a>
                        </div>
                    </div>

                    <div id="cart-summary" class="hidden mt-6 border-t pt-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-2">
                            <span class="text-xl font-semibold">Total:</span>
                            <span id="cart-total-summary" class="text-xl font-bold text-green-600">$0.00</span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            @if(config('fitur.Status'))
                            <button id="print-invoice-btn" onclick="printInvoice()"
                                    class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center">
                                Cetak Invoice (Print Invoice)
                            </button>
                            @endif
                            <button id="order-btn" onclick="placeOrder()"
                                    class="{{ config('fitur.Status') ? 'flex-1' : 'w-full' }} bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center">
                                Pesan Sekarang (Place Order)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load cart from localStorage or initialize empty
        let cart = JSON.parse(localStorage.getItem('userCart')) || [];

        function updateCartDisplay() {
            const cartContent = document.getElementById('cart-content');
            const cartSummary = document.getElementById('cart-summary');
            const cartTotalSummary = document.getElementById('cart-total-summary');

            if (cart.length === 0) {
                cartContent.innerHTML = `
                    <div class="text-center py-12">
                        <div class="mb-6">
                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m4.5-6v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                        <p class="text-gray-500 mb-6">Please go back to POS to add items to your cart.</p>
                        <a href="{{ route('user.pos') }}" class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Go to POS
                        </a>
                    </div>
                `;
                cartSummary.classList.add('hidden');
                return;
            }

            let cartHtml = '<div class="space-y-4">';
            let total = 0;

            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                cartHtml += `
                    <div class="border rounded-lg p-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">${item.name}</h3>
                                <p class="text-sm text-gray-600">Rp ${item.price.toLocaleString('id-ID')} each</p>
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity(${index}, ${item.quantity - 1})"
                                            class="bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded transition-colors">-</button>
                                    <span class="px-3 py-1 bg-gray-100 rounded min-w-[40px] text-center">${item.quantity}</span>
                                    <button onclick="updateQuantity(${index}, ${item.quantity + 1})"
                                            class="bg-gray-300 hover:bg-gray-400 px-3 py-1 rounded transition-colors">+</button>
                                </div>
                                <div class="text-right">
                                    <span class="font-semibold text-lg">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                                </div>
                                <button onclick="removeFromCart(${index})"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors w-full sm:w-auto">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            cartHtml += '</div>';
            cartContent.innerHTML = cartHtml;
            cartTotalSummary.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            cartSummary.classList.remove('hidden');

            // Update cart count in header if exists
            updateCartCount();
        }

        function updateQuantity(index, newQuantity) {
            if (newQuantity <= 0) {
                removeFromCart(index);
                return;
            }

            const item = cart[index];
            item.quantity = newQuantity;
            saveCart();
            updateCartDisplay();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            saveCart();
            updateCartDisplay();
        }

        function saveCart() {
            localStorage.setItem('userCart', JSON.stringify(cart));
        }

        function updateCartCount() {
            const cartCount = document.getElementById('cart-count-header');
            if (cartCount) {
                cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
            }
        }

        function placeOrder() {
            if (cart.length === 0) return;

            // Create form data
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("user.checkout") }}';

            // CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add cart items
            cart.forEach((item, index) => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `items[${index}][id]`;
                idInput.value = item.id;
                form.appendChild(idInput);

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = `items[${index}][name]`;
                nameInput.value = item.name;
                form.appendChild(nameInput);

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = `items[${index}][price]`;
                priceInput.value = item.price;
                form.appendChild(priceInput);

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = `items[${index}][quantity]`;
                quantityInput.value = item.quantity;
                form.appendChild(quantityInput);
            });

            // Add total
            const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total';
            totalInput.value = total.toFixed(2);
            form.appendChild(totalInput);

            // Clear cart after order
            cart = [];
            saveCart();

            document.body.appendChild(form);
            form.submit();
        }

        @if(config('fitur.Status'))
        function printInvoice() {
            if (cart.length === 0) return;

            // Create form data for invoice printing
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("user.cart.print-invoice") }}';
            form.target = '_blank'; // Open in new tab/window

            // CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add cart items
            cart.forEach((item, index) => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `items[${index}][id]`;
                idInput.value = item.id;
                form.appendChild(idInput);

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = `items[${index}][name]`;
                nameInput.value = item.name;
                form.appendChild(nameInput);

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = `items[${index}][price]`;
                priceInput.value = item.price;
                form.appendChild(priceInput);

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = `items[${index}][quantity]`;
                quantityInput.value = item.quantity;
                form.appendChild(quantityInput);
            });

            // Add total
            const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total';
            totalInput.value = total.toFixed(2);
            form.appendChild(totalInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
        @endif

        // Initialize cart display
        updateCartDisplay();
    </script>
</x-app-layout>
