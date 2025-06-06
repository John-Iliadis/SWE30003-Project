@extends('admin.admin')

@section('title', 'Add New Product')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <h1>Add New Product</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand"
                           value="{{ old('brand') }}" required>
                    @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                              name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price ($)</label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                           id="price" name="price" value="{{ old('price') }}" required>
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
                           name="stock" value="{{ old('stock') }}" required>
                    @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_name" class="form-label">Category</label>
                    <div class="input-group">
                        <select class="form-select @error('category_name') is-invalid @enderror" id="category_name"
                                name="category_name" required>
                            <option value="">Select a category</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option
                                    value="{{ $category->category_name }}" {{ old('category_name') == $category->category_name ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                            <option value="new_category">+ Add New Category</option>
                        </select>
                        @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- New Category Fields (initially hidden) -->
                <div class="mb-3" id="new_category_fields" style="display: none;">
                    <label for="new_category_name" class="form-label">New Category Name</label>
                    <input type="text" class="form-control @error('new_category_name') is-invalid @enderror" id="new_category_name"
                           name="new_category_name" value="{{ old('new_category_name') }}">
                    <div class="mt-2">
                        <label for="new_category_description" class="form-label">Category Description</label>
                        <textarea class="form-control @error('new_category_description') is-invalid @enderror" id="new_category_description"
                                  name="new_category_description" rows="2">{{ old('new_category_description') }}</textarea>
                    </div>
                    @error('new_category_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image_url" class="form-label">Image URL</label>
                    <input type="text" class="form-control @error('image_url') is-invalid @enderror" id="image_url"
                           name="image_url" value="{{ old('image_url') }}" required>
                    <small class="form-text text-muted">Provide a URL to the product image</small>
                    @error('image_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                           name="image">
                    <small class="form-text text-muted">Upload a product image (JPG, PNG, or GIF)</small>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_name');
            const newCategoryFields = document.getElementById('new_category_fields');
            
            // Check initial value (in case of form validation error and page reload)
            if (categorySelect.value === 'new_category') {
                newCategoryFields.style.display = 'block';
            }
            
            // Add change event listener
            categorySelect.addEventListener('change', function() {
                if (this.value === 'new_category') {
                    newCategoryFields.style.display = 'block';
                } else {
                    newCategoryFields.style.display = 'none';
                }
            });
        });
    </script>
@endsection
