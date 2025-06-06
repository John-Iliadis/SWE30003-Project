<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">

</head>
<body>

    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>

        {{-- Left image section --}}
        <div class="product_item_left">
            <img src="{{asset($product['image_url'])}}" alt="{{$product['name']}}">
        </div>

        {{-- Middle title and description section --}}
        <div class="product_item_mid">
            <h1>{{$product['brand'] . ' ' . $product['name']}}</h1>
            <p class="product_item_desc">{{$product['description']}}</p>
        </div>

        {{-- Right item price and add to cart section --}}
        <div class="product_item_right">
            <h1>${{(int)$product['price']}}</h1>
            <label><input type="number" id="qty" data-productid="{{$product['product_id']}}" name="quantity" value="1" min="1"></label>
            <button id="add_to_cart_button">Add to cart</button>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')

    <script src="{{asset('js/product.js')}}"></script>

</body>
</html>
