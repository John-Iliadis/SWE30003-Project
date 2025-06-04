<h2>Order Details</h2>
<div class="order-info">
    <div class="detail-row">
        <span>Order Number:</span>
        <span>#{{ $order->order_id }}</span>
    </div>
    <div class="detail-row">
        <span>Date:</span>
        <span>{{ $order->order_date->format('F j, Y') }}</span>
    </div>
    @if($order->creditCard)
    <div class="detail-row">
        <span>Payment Method:</span>
        <span>**** **** **** {{ substr($order->creditCard->card_number, -4) }}</span>
    </div>
    @endif
</div>

<div class="order-items">
    <h3>Items</h3>
    @foreach($order->orderlines as $item)
        <div class="order-item">
            <div class="item-image">
                @if($item->product && $item->product->image)
                <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}">
                @endif
            </div>
            <div class="item-info">
                <h4>{{ $item->product->name ?? 'Product not available' }}</h4>
                <p>Quantity: {{ $item->quantity }}</p>
                <p>Price: ${{ number_format($item->product->price ?? 0, 2) }}</p>
                <p>Total: ${{ number_format(($item->product->price ?? 0) * $item->quantity, 2) }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="order-summary">
    <div class="summary-row">
        <span>Subtotal:</span>
        <span>${{ number_format($order->orderlines->sum(function($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        }), 2) }}</span>
    </div>
    <div class="summary-row">
        <span>Total:</span>
        <span>${{ number_format($order->orderlines->sum(function($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        }), 2) }}</span>
    </div>
</div>