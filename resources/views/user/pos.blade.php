<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Point of Sale') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('user.cart') }}" id="cart-button" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition-all duration-300 relative">
                    Cart
                    <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">0</span>
                </a>
                <a href="{{ route('user.history') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View History
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Products Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Category Filter -->
                    <form method="GET" class="mb-6">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                        <select name="category_id" id="category_id" onchange="this.form.submit()" class="border-gray-300 rounded-md">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </form>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <div class="border rounded-lg p-4 product-card" data-product-id="{{ $product->id }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-32 object-cover mb-2">
                            @endif
                            <h3 class="font-semibold">{{ $product->name }}</h3>
                            <p class="text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">
                                Stock: <span class="stock-display" data-current-stock="{{ $product->stock }}">{{ $product->stock }}</span>
                            </p>
                            <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                            <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-2 add-to-cart-btn"
                                    @if($product->stock <= 0) disabled style="background-color: #9CA3AF;" @endif>
                                @if($product->stock <= 0)
                                    Out of Stock
                                @else
                                    Add to Cart
                                @endif
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        <button onclick="alert('JavaScript is working!')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Test JavaScript
        </button>
    </div> --}}

    <script>
        // Load cart from localStorage or initialize empty
        let cart = JSON.parse(localStorage.getItem('userCart')) || [];

        function addToCart(id, name, price, stock) {
            console.log('Adding to cart:', id, name, price, stock);

            // Find the product card
            const productCard = document.querySelector(`[data-product-id="${id}"]`);
            const stockDisplay = productCard.querySelector('.stock-display');
            const addToCartBtn = productCard.querySelector('.add-to-cart-btn');

            // Get current visual stock
            let currentStock = parseInt(stockDisplay.textContent);

            // Check if there's enough stock visually
            if (currentStock <= 0) {
                alert('Product is out of stock!');
                return;
            }

            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity += 1;
                saveCart();
                updateCartCount();
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
                saveCart();
                updateCartCount();
            }

            // Visually decrease stock
            currentStock -= 1;
            stockDisplay.textContent = currentStock;
            stockDisplay.setAttribute('data-current-stock', currentStock);

            // Update button state if stock is 0
            if (currentStock <= 0) {
                addToCartBtn.disabled = true;
                addToCartBtn.style.backgroundColor = '#9CA3AF';
                addToCartBtn.textContent = 'Out of Stock';
            }
        }

        function animateCartButton() {
            const cartButton = document.getElementById('cart-button');
            if (cartButton) {
                // Add bounce animation
                cartButton.classList.add('animate-bounce');
                cartButton.style.backgroundColor = '#10B981'; // Green color for success

                // Remove animation after 1 second
                setTimeout(() => {
                    cartButton.classList.remove('animate-bounce');
                    cartButton.style.backgroundColor = ''; // Reset to original
                }, 1000);
            }
        }

        function saveCart() {
            localStorage.setItem('userCart', JSON.stringify(cart));
        }

        function updateCartCount() {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
            }
        }

        // Initialize cart count on page load
        updateCartCount();
    </script>
</x-app-layout>
