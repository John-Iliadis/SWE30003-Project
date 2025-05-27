@extends('layouts.app')

@section('content')
<div class="admin-container">
    <h1>Product Management</h1>
    
    <a href="{{ route('admin.products.create') }}" class="btn">Add New Product</a>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-edit">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $products->links() }}
</div>
@endsection