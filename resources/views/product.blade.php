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
            <img src="{{asset('img/phones/iphone16promax.png')}}" alt="Iphone 16 Pro Max">
        </div>

        <div class="product_item_mid">
            <h1>Apple - iPhone 16 Pro Max</h1>
            <p class="product_item_desc">iPhone 16 Pro Max. Built for Apple Intelligence. Featuring a stunning
                titanium design. Camera Control. 4K 120 fps Dolby Vision. And A18 Pro chip.
            </p>
        </div>

        <div class="product_item_right">
            <h1>$2000</h1>
            <label><input type="number" name="quantity" value="1"></label>
            <button>Add to cart</button>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

</body>
</html>
