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

    @include('header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div id="column_title_row">
            <span class="column_title" id="item_column">Item</span>
            <span class="column_title" id="unit_price_column">Unit Price</span>
            <span class="column_title" id="qty_column">Quantity</span>
            <span class="column_title" id="total_column">Total</span>
            <span class="column_title" id="clear_column">Clear Cart</span>
        </div>

        <div class="cart_listing">
            <div class="cart_img_container">
                <img class="cart_listing_img" src="{{asset('img/phones/iphone16promax.png')}}" alt="iphone 16 pro max">
            </div>
            <div class="cart_title_container">
                <span class="cart_listing_title">Apple iPhone 16 Pro Max</span>
            </div>
            <div class="cart_price_container">
                <span class="cart_listing_price">$2000</span>
            </div>
            <div class="cart_qty_container">
                <label><input type="number" name="min_price" class="qty_num_input"></label>
            </div>
            <div class="cart_total_container">
                <span class="cart_total_price">$2000</span>
            </div>
            <div class="cart_clear_container">
                <button class="remove_item">Remove</button>
            </div>
        </div>

        <div class="cart_listing">
            <div class="cart_img_container">
                <img class="cart_listing_img" src="{{asset('img/phones/iphone16promax.png')}}" alt="iphone 16 pro max">
            </div>
            <div class="cart_title_container">
                <span class="cart_listing_title">Apple iPhone 16 Pro Max</span>
            </div>
            <div class="cart_price_container">
                <span class="cart_listing_price">$2000</span>
            </div>
            <div class="cart_qty_container">
                <label><input type="number" name="min_price" class="qty_num_input"></label>
            </div>
            <div class="cart_total_container">
                <span class="cart_total_price">$2000</span>
            </div>
            <div class="cart_clear_container">
                <button class="remove_item">Remove</button>
            </div>
        </div>

        <div id="cart_bottom">
            <p id="total_price">Total: $2000</p>
            <button id="checkout_button">Checkout</button>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

</body>
</html>
