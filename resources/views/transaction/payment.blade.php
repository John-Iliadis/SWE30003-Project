<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/checkout.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">

</head>
<body>

    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div class="auth-container">
            <h2>Checkout</h2>

            {{-- Print errors, if there are any --}}
            @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Prompt message if the user is not signed in --}}
            @if(!Auth::check())
                <div style="text-align: center; margin-bottom: 20px;">
                    <p>Already have an account? <a href="/login" style="color: #4CAF50; text-decoration: underline;">Sign in</a> to use your saved information.</p>
                </div>
            @endif

            {{-- Checkout form --}}
            <form action="/process-payment" method="POST">
                @csrf

                {{-- Render all products in the cart --}}
                @if(isset($cartItems) && count($cartItems) > 0)
                    <div class="form-section">
                        <h3 class="section-heading">Order Summary</h3>
                        <div class="heading-line"></div>
                        <div class="order-summary">
                            <div style="margin-bottom: 15px;">
                                @foreach($cartItems as $item)
                                    <div class="order-item">
                                        <div>
                                            <span style="font-weight: bold;">{{ $item['product']['name'] }}</span>
                                            <span style="color: #666; margin-left: 10px;">x {{ $item['quantity'] }}</span>
                                        </div>
                                        <div>${{ number_format($item['subtotal'], 2) }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-total">
                                <div class="price-row total-price">
                                    <span>Total:</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Personal information form section --}}
                <div class="form-section">
                    <h3 class="section-heading">Personal Information</h3>
                    <div class="heading-line"></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" placeholder="Full name" value="{{ $userDetails->name ?? old('name') }}" required>
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email address" value="{{ Auth::check() ? Auth::user()->email : old('email') }}" required>
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="1234 567 890" value="{{ $userDetails->phone_number ?? old('phone_number') }}" required>
                        @error('phone_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Address information form section --}}
                <div class="form-section">
                    <h3 class="section-heading">Address Information</h3>
                    <div class="heading-line"></div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" placeholder="Street address" value="{{ $userDetails->address ?? old('address') }}" required>
                        @error('address')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="City" value="{{ $userDetails->city ?? old('city') }}" required>
                            @error('city')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="zip_code">Post Code</label>
                            <input type="text" id="zip_code" name="zip_code" placeholder="1234" value="{{ $userDetails->zip_code ?? old('zip_code') }}" required>
                            @error('zip_code')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" placeholder="State/Province" value="{{ $userDetails->state ?? old('state') }}" required>
                            @error('state')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" placeholder="Country" value="{{ $userDetails->country ?? old('country') }}" required>
                            @error('country')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Payment information form section --}}
                <div class="form-section">
                    <h3 class="section-heading">Payment Information</h3>
                    <div class="heading-line"></div>
                    <div class="form-group">
                        <label for="cardholder_name">Card Holder Name</label>
                        <input type="text" id="cardholder_name" name="cardholder_name" placeholder="Name on card" value="{{ $creditCard->cardholder_name ?? old('cardholder_name') }}" required>
                        @error('cardholder_name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" value="{{ $creditCard->card_number ?? old('card_number') }}" required>
                        @error('card_number')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_expire">Expiry Date</label>
                            <input type="month" id="card_expire" name="card_expire" min="{{ date('Y-m') }}" value="{{ $creditCard ? date('Y-m', strtotime($creditCard->expiration_year.'-'.$creditCard->expiration_month)) : old('card_expire') }}" required>
                            @error('card_expire')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" maxlength="4" placeholder="123" required>
                            @error('cvv')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="submit-button">Complete Order</button>
            </form>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')

    <script src="{{asset('js/payment-validation.js')}}"></script>
</body>
</html>
