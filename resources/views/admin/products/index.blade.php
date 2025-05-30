@extends('layouts.app') {{-- Or your admin layout e.g., @extends('layouts.admin') --}}

@section('title', 'Manage Products')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Manage Products</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td> {{-- Assuming Product model has a 'category' relationship --}}
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $products->links() }} {{-- For pagination --}}
    </div>

</div>
@endsection