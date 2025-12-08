<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cart') }} (<span id="cart-count-header">0</span>)
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Back to POS
                </a>
                <a href="{{ route('user.history') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    View History
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="cart-content">
                        <p class="text-center text-gray-500 py-8">Your cart is managed on the POS page. Please go back to POS to add items to cart.</p>
                        <div class="text-center">
                            <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                Go to POS
                            </a>
                        </div>
                    </div>

                    <div id="cart-summary" class="hidden mt-6 border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold">Total:</span>
                            <span id="cart-total-summary" class="text-lg font-semibold">$0.00</span>
                        </div>
                        <button id="order-btn" onclick="placeOrder()"
                                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                            Pesan Sekarang (Place Order)
                        </button>
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
                    <p class="text-center text-gray-500 py-8">Your cart is empty. Please go back to POS to add items.</p>
                    <div class="text-center">
                        <a href="{{ route('user.pos') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
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
                    <div class="flex items-center justify-between p-4 border rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3 class="font-semibold">${item.name}</h3>
                                <p class="text-sm text-gray-600">Rp ${item.price.toLocaleString('id-ID')} each</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity(${index}, ${item.quantity - 1})"
                                        class="bg-gray-300 px-3 py-1 rounded">-</button>
                                <span class="px-3">${item.quantity}</span>
                                <button onclick="updateQuantity(${index}, ${item.quantity + 1})"
                                        class="bg-gray-300 px-3 py-1 rounded">+</button>
                            </div>
                            <span class="font-semibold">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                            <button onclick="removeFromCart(${index})"
                                    class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
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

        // Initialize cart display
        updateCartDisplay();
    </script>
</x-app-layout>
