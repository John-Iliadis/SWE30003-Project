<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/history.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
</head>
<body>
    @include('partials.header')
    <div id="spacing_top" style="height: 80px"></div>
    <main>
        @isset($orders)
        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="order-item {{ $selectedOrder && $selectedOrder->order_id == $order->order_id ? 'active' : '' }}" 
                    data-order-id="{{ $order->order_id }}">
                    <div class="order-summary">
                        <span class="order-number">Order #{{ $order->order_id }}</span>
                        <span class="order-date">{{ $order->order_date->format('M d, Y') }}</span>
                    </div>
                    <div class="order-total">
                        ${{ number_format($order->orderlines->sum(function($item) {
                            return $item->product ? $item->product->price * $item->quantity : 0;
                        }), 2) }}
                    </div>
                </div>
            @endforeach

            <div class="order-details">
            @if($selectedOrder)
                @include('partials.order_details', ['order' => $selectedOrder])
            @else
                <div class="no-order-selected">
                    <p>Select an order to view details</p>
                </div>
            @endif
        </div>
        @else
        <div class="empty_orders_container">
            <h1>Your order history is empty</h1>
            <p>Check out some of our most popular products below, or feel free to browse!</p>
            <a href="/catalogue">
                <button class="start_shopping_button">Start Shopping</button>
            </a>
        </div>
        @endif
        @if($orders->count() > 0)
        <script>
        document.querySelectorAll('.order-item').forEach(item => {
            item.addEventListener('click', function() {
                const orderId = this.dataset.orderId;
                
                // Update URL
                history.pushState(null, null, `/account/orders/${orderId}`);
                
                // Fetch order details
                fetch(`/account/orders/${orderId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    document.querySelector('.order-details').innerHTML = data.html;
                    
                    // Update active state
                    document.querySelectorAll('.order-item').forEach(item => {
                        item.classList.toggle('active', item.dataset.orderId === orderId);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading order details');
                });
            });
        });
        </script>
    @endif
    @else
        <div class="error-container">
            <h1>Order history could not be loaded</h1>
            <p>Please try again later or contact support</p>
        </div>
    @endisset
    </main>
    @include('partials.footer')
</body>
</html>
