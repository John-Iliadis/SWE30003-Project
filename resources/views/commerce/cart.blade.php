<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/cart.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">

</head>
<body>

@include('partials.header')

<div id="spacing_top" style="height: 80px"></div>

<main>
    @if(count($cart_items) > 0)
        <div id="column_title_row">
            <span class="column_title" id="item_column">Item</span>
            <span class="column_title" id="unit_price_column">Unit Price</span>
            <span class="column_title" id="qty_column">Quantity</span>
            <span class="column_title" id="total_column">Subtotal</span>
            <button class="column_title" id="clear_cart_button">Clear Cart</button>
        </div>

        @foreach($cart_items as $cart_item)
            @php
                $product = $cart_item['product'];
                $quantity = $cart_item['quantity'];
                $subtotal = $cart_item['subtotal'];
            @endphp

            <div class="cart_listing" data-productid="{{$product['product_id']}}">
                <div class="cart_img_container">
                    <img class="cart_listing_img" src="{{ asset($product['image_url']) }}" alt="{{ $product['name'] }}">
                </div>
                <div class="cart_title_container">
                    <span class="cart_listing_title">{{ $product['brand'] . ' ' . $product['name'] }}</span>
                </div>
                <div class="cart_price_container">
                    <span class="cart_listing_price">{{ '$' . (int) $product['price'] }}</span>
                </div>
                <div class="cart_qty_container">
                    <label><input type="number" name="min_price" class="qty_num_input" value="{{ $quantity }}" min="1"></label>
                </div>
                <div class="cart_total_container">
                    <span class="cart_total_price">{{ '$' . $subtotal }}</span>
                </div>
                <div class="cart_clear_container">
                    <button class="remove_item" data-productId="{{$product['product_id']}}">Remove</button>
                </div>
            </div>
        @endforeach

        <div id="cart_bottom">
            <p id="total_price">Total: {{ '$' . (int)$total }}</p>
            <button id="checkout_button">Checkout</button>
        </div>
    @else
        <div id="empty_cart_container">
            <h1>Your shopping cart is empty</h1>
            <p>Check out some of our most popular products below, or feel free to browse!</p>
            <a href="/catalogue">
                <button id="start_shopping_button">Start Shopping</button>
            </a>
        </div>
    @endif
</main>

<div id="spacing_bottom" style="height: 80px"></div>

@include('partials.footer')

<script src="{{asset('js/cart.js')}}"></script>

</body>
</html>
