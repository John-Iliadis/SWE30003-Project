<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/cart.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
    <style>
        .confirmation-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .confirmation-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .confirmation-header h1 {
            color: #4CAF50;
        }

        .order-details {
            margin-bottom: 30px;
        }

        .order-details h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .order-info strong {
            font-weight: bold;
        }

        .continue-shopping {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .continue-shopping:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div class="confirmation-container">
            <div class="confirmation-header">
                <h1>Order Confirmed!</h1>
                <p>Thank you for your purchase. Your order has been successfully placed.</p>
            </div>

            <div class="order-details">
                <h2>Order Details</h2>
                <div class="order-info">
                    <span><strong>Order Number:</strong></span>
                    <span>{{ $order->order_id }}</span>
                </div>
                <div class="order-info">
                    <span><strong>Order Date:</strong></span>
                    <span>{{ date('F j, Y', strtotime($order->order_date)) }}</span>
                </div>
            </div>

            <div class="order-details">
                <h2>Items Purchased</h2>

                <div id="column_title_row">
                    <span class="column_title" id="item_column">Item</span>
                    <span class="column_title" id="unit_price_column">Unit Price</span>
                    <span class="column_title" id="qty_column">Quantity</span>
                    <span class="column_title" id="total_column">Total</span>
                </div>

                @foreach($products as $item)
                    <div class="cart_listing">
                        <div class="cart_img_container">
                            <img class="cart_listing_img" src="{{asset($item['product']->image_url)}}" alt="{{$item['product']->name}}">
                        </div>
                        <div class="cart_title_container">
                            <span class="cart_listing_title">{{$item['product']->name}}</span>
                        </div>
                        <div class="cart_price_container">
                            <span class="cart_listing_price">${{$item['product']->price}}</span>
                        </div>
                        <div class="cart_qty_container">
                            <span>{{$item['quantity']}}</span>
                        </div>
                        <div class="cart_total_container">
                            <span class="cart_total_price">${{$item['total']}}</span>
                        </div>
                    </div>
                @endforeach

                <div id="cart_bottom">
                    <p id="total_price">Total: ${{$total}}</p>
                </div>
            </div>

            <a href="{{ url('/catalogue') }}" class="continue-shopping">Continue Shopping</a>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')

</body>
</html>
