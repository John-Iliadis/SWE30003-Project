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

            <label class="filter_checkbox">
                <input type="checkbox" name="category" value="phones">
                <span class="checkbox_title">Phones</span>
                <span class="checkbox_count">15</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="category" value="Laptops">
                <span class="checkbox_title">Laptops</span>
                <span class="checkbox_count">12</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="category" value="cameras">
                <span class="checkbox_title">Cameras</span>
                <span class="checkbox_count">20</span>
            </label>

            <div class="filter_section">Brand</div>

            <label class="filter_checkbox">
                <input type="checkbox" name="brand" value="samsung">
                <span class="checkbox_title">Samsung</span>
                <span class="checkbox_count">15</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="brand" value="apple">
                <span class="checkbox_title">Apple</span>
                <span class="checkbox_count">12</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="brand" value="google">
                <span class="checkbox_title">Google</span>
                <span class="checkbox_count">20</span>
            </label>

            <div class="filter_section">Price</div>

            <div id="price_range_input">
                <label><input type="number" name="min_price" class="price_text_input" placeholder="$"></label>
                <span>to</span>
                <label><input type="number" name="max_price" class="price_text_input" placeholder="$"></label>
                <button id="price_set_button">Set</button>
            </div>

            <label class="filter_checkbox">
                <input type="checkbox" name="price_range" value="0 50">
                <span class="checkbox_title">$0 - $50</span>
                <span class="checkbox_count">20</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="price_range" value="50 100">
                <span class="checkbox_title">$50 - $100</span>
                <span class="checkbox_count">20</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="price_range" value="100 200">
                <span class="checkbox_title">$100 - $200</span>
                <span class="checkbox_count">20</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="price_range" value="200 500">
                <span class="checkbox_title">$200 - $500</span>
                <span class="checkbox_count">20</span>
            </label>

            <label class="filter_checkbox">
                <input type="checkbox" name="price_range" value="500 1000">
                <span class="checkbox_title">$500 - $1000</span>
                <span class="checkbox_count">20</span>
            </label>

            <button id="reset_button">Reset Filters</button>
        </div>

        <div id="catalogue">

            @foreach($products as $product)
                <div class="catalogue_item">
                    <div class="catalogue_item_left">
                        <a href="/product">
                            <img src="{{asset($product['image_url'])}}" alt="{{$product['name']}}">
                        </a>
                    </div>
                    <div class="catalogue_item_mid">
                        <a href="#"><h1>{{$product['brand'] . ' ' . $product['name']}}</h1></a>
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
