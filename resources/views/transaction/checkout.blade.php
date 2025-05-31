<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/cart.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">

</head>
<body>

    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <h1>Checkout</h1>

        <div id="column_title_row">
            <span class="column_title" id="item_column">Item</span>
            <span class="column_title" id="unit_price_column">Unit Price</span>
            <span class="column_title" id="qty_column">Quantity</span>
            <span class="column_title" id="total_column">Total</span>
        </div>

        @php
            $cartItems = session('cart', []);
            $total = 0;
        @endphp

        @foreach($cartItems as $productId => $item)
            @php
                $product = \App\Models\Product::find($productId);
                $itemTotal = $product->price * $item['quantity'];
                $total += $itemTotal;
            @endphp
            <div class="cart_listing">
                <div class="cart_img_container">
                    <img class="cart_listing_img" src="{{asset($product->image_url)}}" alt="{{$product->name}}">
                </div>
                <div class="cart_title_container">
                    <span class="cart_listing_title">{{$product->name}}</span>
                </div>
                <div class="cart_price_container">
                    <span class="cart_listing_price">${{$product->price}}</span>
                </div>
                <div class="cart_qty_container">
                    <span>{{$item['quantity']}}</span>
                </div>
                <div class="cart_total_container">
                    <span class="cart_total_price">${{$itemTotal}}</span>
                </div>
            </div>
        @endforeach

        <div id="cart_bottom">
            <p id="total_price">Total: ${{$total}}</p>
            <a href="{{ route('transaction.payment') }}" class="button" id="proceed_to_payment">Proceed to Payment</a>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')

</body>
</html>
