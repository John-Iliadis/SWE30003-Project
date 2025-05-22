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

    @include('header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div class="product_item_left">
            <img src="{{asset($product['image_url'])}}" alt="{{$product['name']}}">
        </div>

        <div class="product_item_mid">
            <h1>{{$product['brand'] . ' ' . $product['name']}}</h1>
            <p class="product_item_desc">{{$product['description']}}</p>
        </div>

        <div class="product_item_right">
            <h1>{{$product['price']}}</h1>
            <label><input type="number" name="quantity" value="1"></label>
            <button>Add to cart</button>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

</body>
</html>
