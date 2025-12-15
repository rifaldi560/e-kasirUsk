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
                {{-- <a href="{{ route('user.history') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View History
                </a> --}}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Products Section -->
            <div class="card-responsive">
                <div class="p-4 sm:p-6 text-gray-900">
                    <!-- Search and Filter -->
                    {{-- <div class="flex flex-col md:flex-row gap-4 mb-6"> --}}
                        <!-- Search -->
                        {{-- <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="search" name="search" value="{{ $search }}"
                                       placeholder="Search by product name..."
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div> --}}

                        <!-- Category Filter -->
                        {{-- <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Category</label>
                            <select name="category_id" id="category_id" class="border-gray-300 rounded-md">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Filter Button -->
                        {{-- <div class="flex items-end">
                            <button type="button" onclick="applyFilters()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Apply Filters
                            </button>
                        </div>
                    </div> --}}

                    <!-- Products Grid -->
                    <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <div class="border rounded-lg p-4 product-card" data-product-id="{{ $product->id }}">
                            <!-- Image Container - Always present for consistent layout -->
                            <div class="w-full h-32 bg-gray-100 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <!-- Placeholder for products without image -->
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">No Image</span>
                                    </div>
                                @endif
                            </div>
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

                    <!-- Skeleton Loading Placeholder -->
                    <div id="products-skeleton" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 hidden loading-transition">
                        @for($i = 0; $i < 6; $i++)
                        <div class="border border-gray-200 rounded-xl p-4 shadow-sm bg-white animate-pulse-enhanced">
                            <!-- Product Image Placeholder -->
                            <div class="relative mb-3">
                                <div class="w-full h-36 skeleton-shimmer rounded-lg mb-2"></div>
                                <div class="absolute top-2 right-2 w-6 h-6 skeleton-shimmer rounded-full"></div>
                            </div>

                            <!-- Product Title -->
                            <div class="space-y-2 mb-3">
                                <div class="h-5 skeleton-shimmer rounded w-4/5"></div>
                                <div class="h-4 skeleton-shimmer rounded w-2/3"></div>
                            </div>

                            <!-- Product Price -->
                            <div class="flex justify-between items-center mb-3">
                                <div class="h-6 skeleton-shimmer rounded w-20"></div>
                                <div class="h-4 skeleton-shimmer rounded w-16"></div>
                            </div>

                            <!-- Category Badge -->
                            <div class="h-5 skeleton-shimmer rounded-full w-24 mb-4"></div>

                            <!-- Add to Cart Button -->
                            <div class="h-10 skeleton-shimmer rounded-lg w-full"></div>
                        </div>
                        @endfor
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

            // Show success notification
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `"${name}" berhasil ditambahkan ke cart kamu`,
                timer: 2000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });

            animateCartButton();
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

        // Show skeleton loading briefly for demonstration
        showSkeleton();

        function showSkeleton() {
            const productsGrid = document.getElementById('products-grid');
            const skeleton = document.getElementById('products-skeleton');

            if (productsGrid && skeleton) {
                productsGrid.classList.add('hidden');
                skeleton.classList.remove('hidden');

                // Hide skeleton after a brief delay
                setTimeout(() => {
                    skeleton.classList.add('hidden');
                    productsGrid.classList.remove('hidden');
                }, 800);
            }
        }

        function hideSkeleton() {
            const productsGrid = document.getElementById('products-grid');
            const skeleton = document.getElementById('products-skeleton');

            if (productsGrid && skeleton) {
                skeleton.classList.add('hidden');
                productsGrid.classList.remove('hidden');
            }
        }

        function showLoadingState() {
            const productsGrid = document.getElementById('products-grid');
            const skeleton = document.getElementById('products-skeleton');

            if (productsGrid && skeleton) {
                // Add fade out effect to current products
                productsGrid.style.opacity = '0.5';
                productsGrid.style.pointerEvents = 'none';

                // Show skeleton with a slight delay for smooth transition
                setTimeout(() => {
                    skeleton.classList.remove('hidden');
                    skeleton.style.opacity = '1';
                }, 200);
            }
        }

        function hideLoadingState() {
            const productsGrid = document.getElementById('products-grid');
            const skeleton = document.getElementById('products-skeleton');

            if (productsGrid && skeleton) {
                // Fade out skeleton
                skeleton.style.opacity = '0';

                setTimeout(() => {
                    skeleton.classList.add('hidden');
                    skeleton.style.opacity = '1'; // Reset for next use

                    // Fade back in products grid
                    productsGrid.style.opacity = '1';
                    productsGrid.style.pointerEvents = 'auto';
                }, 300);
            }
        }

        function applyFilters() {
            const searchInput = document.getElementById('search');
            const categorySelect = document.getElementById('category_id');

            // Build URL with parameters
            const params = new URLSearchParams(window.location.search);

            if (searchInput.value.trim()) {
                params.set('search', searchInput.value.trim());
            } else {
                params.delete('search');
            }

            if (categorySelect.value) {
                params.set('category_id', categorySelect.value);
            } else {
                params.delete('category_id');
            }

            // Navigate to the filtered URL
            window.location.href = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        }

        // Add enter key support for search
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Auto-clear filters when search becomes empty
        document.getElementById('search').addEventListener('input', function(e) {
            if (e.target.value.trim() === '') {
                // Clear search parameter and navigate to base URL
                const params = new URLSearchParams(window.location.search);
                params.delete('search');

                // If category is also selected, keep it, otherwise go to clean URL
                if (document.getElementById('category_id').value) {
                    params.set('category_id', document.getElementById('category_id').value);
                }

                const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.location.href = newUrl;
            }
        });
    </script>
</x-app-layout>
