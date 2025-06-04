<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
    <style>
        .auth-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .auth-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: black;
        }

        .form-section {
            margin-bottom: 1.5rem;
        }

        .section-heading {
            margin: 1.5rem 0 0.5rem 0;
            color: #444;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .heading-line {
            height: 2px;
            background: linear-gradient(to right, #4CAF50, #ddd);
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .password-hint {
            margin: 0.2rem 0 0.8rem 0;
            color: gray;
            font-size: 0.7rem;
        }

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 1rem;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .order-summary {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .order-total {
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-price {
            font-weight: bold;
            margin-top: 10px;
            font-size: 18px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                padding: 1.5rem;
                margin: 1rem;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 1rem;
            }

            .form-group input {
                padding: 0.65rem;
            }

            .section-heading {
                font-size: 1.1rem;
            }

            .heading-line {
                margin-bottom: 0.8rem;
            }
        }
    </style>
</head>
<body>

    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div class="auth-container">
            <h2>Checkout</h2>
            @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!Auth::check())
                <div style="text-align: center; margin-bottom: 20px;">
                    <p>Already have an account? <a href="{{ route('login') }}" style="color: #4CAF50; text-decoration: underline;">Sign in</a> to use your saved information.</p>
                </div>
            @endif

            <form action="{{ route('transaction.process') }}" method="POST">
                @csrf

                @if(isset($products) && count($products) > 0)
                    <div class="form-section">
                        <h3 class="section-heading">Order Summary</h3>
                        <div class="heading-line"></div>
                        <div class="order-summary">
                            <div style="margin-bottom: 15px;">
                                @foreach($products as $item)
                                    <div class="order-item">
                                        <div>
                                            <span style="font-weight: bold;">{{ $item['product']->name }}</span>
                                            <span style="color: #666; margin-left: 10px;">x {{ $item['quantity'] }}</span>
                                        </div>
                                        <div>${{ number_format($item['total'], 2) }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-total">
                                <div class="price-row">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="price-row total-price">
                                    <span>Total:</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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
