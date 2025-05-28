<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <input type="radio" class="filter_val" name="sort" value="alphabetical_ascending" checked>
                Alphabetical Ascending
            </label>

            <label class="sort_radio">
                <input type="radio" class="filter_val" name="sort" value="alphabetical_descending">
                Alphabetical Descending
            </label>

            <label class="sort_radio">
                <input type="radio" class="filter_val" name="sort" value="price_ascending">
                Price Ascending
            </label>

            <label class="sort_radio">
                <input type="radio" class="filter_val" name="sort" value="price_descending">
                Price Descending
            </label>

            <div class="filter_section">Category</div>

            @foreach($categories as $category)
                <label class="filter_checkbox">
                    <input type="checkbox" class="filter_val" name="category" value="{{$category['category_name']}}">
                    <span class="checkbox_title">{{$category['category_name']}}</span>
                </label>
            @endforeach

            <div class="filter_section">Brand</div>

            @foreach($brands as $brand)
                <label class="filter_checkbox">
                    <input type="checkbox" class="filter_val" name="brand" value="{{$brand}}">
                    <span class="checkbox_title">{{$brand}}</span>
                </label>
            @endforeach

            <div class="filter_section">Price</div>

            <div id="price_range_input">
                <label><input type="number" name="min_price" class="price_text_input" min="0" placeholder="$"></label>
                <span>to</span>
                <label><input type="number" name="max_price" class="price_text_input" min="0" placeholder="$"></label>
                <button id="price_set_button">Set</button>
            </div>

            @foreach($price_ranges as $range)
                <label class="filter_checkbox">
                    <input type="checkbox" class="filter_val" name="price_range" value="{{$range[0] . ' ' . $range[1]}}">
                    <span class="checkbox_title">${{$range[0]}} - ${{$range[1]}}</span>
                </label>
            @endforeach

            <hr id="filter_line">

            <button id="reset_button">Reset Filters</button>
        </div>

        <div id="catalogue">
            @include('catalogue_items', ['products' => $products])
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

    <script src="{{asset('js/catalogue.js')}}"></script>

</body>
</html>
