<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" value="{{ $product->name }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ $product->price }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" id="stock" min="1" value="{{ $product->stock }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 mb-2">
                            @endif
                            <input type="file" name="image" id="image" class="mt-1 block w-full" accept="image/*">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
