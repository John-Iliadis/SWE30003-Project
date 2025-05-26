<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/catalogue.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">

</head>
<body>

    @include('header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div id="filter">
            <div class="filter_section">Sort</div>

            <label class="sort_radio">
                <input type="radio" name="sort" value="alphabetical_ascending" checked>
                Alphabetical Ascending
            </label>

            <label class="sort_radio">
                <input type="radio" name="sort" value="alphabetical_descending">
                Alphabetical Descending
            </label>

            <label class="sort_radio">
                <input type="radio" name="sort" value="price_ascending">
                Price Ascending
            </label>

            <label class="sort_radio">
                <input type="radio" name="sort" value="price_descending">
                Price Descending
            </label>

            <div class="filter_section">Category</div>

            @foreach($categories as $category)
                <label class="filter_checkbox">
                    <input type="checkbox" name="category" value="{{$category['category_name']}}">
                    <span class="checkbox_title">{{$category['category_name']}}</span>
                </label>
            @endforeach

            <div class="filter_section">Brand</div>

            @foreach($brands as $brand)
                <label class="filter_checkbox">
                    <input type="checkbox" name="brand" value="{{$brand}}">
                    <span class="checkbox_title">{{$brand}}</span>
                </label>
            @endforeach

            <div class="filter_section">Price</div>

            <div id="price_range_input">
                <label><input type="number" name="min_price" class="price_text_input" placeholder="$"></label>
                <span>to</span>
                <label><input type="number" name="max_price" class="price_text_input" placeholder="$"></label>
                <button id="price_set_button">Set</button>
            </div>

            @foreach($price_ranges as $range)
                <label class="filter_checkbox">
                    <input type="checkbox" name="price_range" value="{{$range[0] . ' ' . $range[1]}}">
                    <span class="checkbox_title">${{$range[0]}} - ${{$range[1]}}</span>
                </label>
            @endforeach

            <hr id="filter_line">

            <button id="reset_button">Reset Filters</button>
        </div>

        <div id="catalogue">

            @foreach($products as $product)
                <div class="catalogue_item">
                    <div class="catalogue_item_left">
                        <a href="/product/{{$product['product_id']}}">
                            <img src="{{asset($product['image_url'])}}" alt="{{$product['name']}}">
                        </a>
                    </div>
                    <div class="catalogue_item_mid">
                        <a href="/product/{{$product['product_id']}}"><h1>{{$product['brand'] . ' ' . $product['name']}}</h1></a>
                        <p class="catalogue_item_desc">{{$product['description']}}</p>
                    </div>
                    <div class="catalogue_item_right">
                        <h2>{{'$' . $product['price']}}</h2>
                        <button>Add to cart</button>
                    </div>
                </div>
            @endforeach

        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

</body>
</html>
