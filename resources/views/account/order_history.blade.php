<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order History</title>

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order_history.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>
    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        @if(count($orders) > 0)
            @foreach($orders as $order)
                <div class="order">
                    <h1 class="order_title">Order Number #{{ $order['order_id'] }}</h1>

                    <hr class="separator_line">

                    <h2 class="order_info order_date">Order Date: {{ $order['date'] }}</h2>
                    <h2 class="order_info order_total">Total: ${{ $order['total'] }}</h2>

                    @foreach($order['items'] as $item)
                        @php
                            $product = $item['product'];
                        @endphp

                        <hr class="separator_line">

                        <div class="order_item_container">
                            <div class="order_item_container_left">
                                <a href="/product/{{$product['product_id']}}" class="item_img_a">
                                    <img class="item_img" src="{{asset($product['image_url'])}}" alt="{{$product['name']}}">
                                </a>
                            </div>
                            <div class="order_item_container_mid">
                                <div class="top_item">
                                    <h2>{{ $product['brand'] . ' ' . $product['name'] }}</h2>
                                    <p>Unit Price: ${{ $product['price'] }}</p>
                                    <p>Quantity: {{$item['quantity']}}</p>
                                </div>

                                <div class="bottom_item">
                                    <h3>Subtotal: ${{ $item['subtotal'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <div id="empty_orders_container">
                <h1>You haven't made any orders.</h1>
                <p>Let's change that!</p>
                <a href="/catalogue">
                    <button id="start_shopping_button">Start Shopping</button>
                </a>
            </div>
        @endif
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')
</body>
</html>
