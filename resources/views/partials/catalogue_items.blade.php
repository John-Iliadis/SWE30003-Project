
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
            <h2>{{'$' . (int)$product['price']}}</h2>
            <button class="add_to_cart_button" data-productId="{{$product['product_id']}}">Add to cart</button>
        </div>
    </div>
@endforeach
