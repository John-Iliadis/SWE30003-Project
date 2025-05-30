<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment</title>

    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
    <style>
        .payment-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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

        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    @include('header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <h1 style="text-align: center; margin-bottom: 30px;">Checkout Information</h1>

        @if(!Auth::check())
            <div style="text-align: center; margin-bottom: 20px;">
                <p>Already have an account? <a href="{{ route('login') }}" style="color: #4CAF50; text-decoration: underline;">Sign in</a> to use your saved information.</p>
            </div>
        @endif

        <form class="payment-form" action="{{ route('transaction.process') }}" method="POST">
            @csrf

            <h2>Delivery Information</h2>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ Auth::check() ? Auth::user()->email : old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ $userDetails->name ?? old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ $userDetails->phone_number ?? old('phone_number') }}" required>
                @error('phone_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Delivery Address</label>
                <input type="text" id="address" name="address" value="{{ $userDetails->address ?? old('address') }}" required>
                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ $userDetails->city ?? old('city') }}" required>
                    @error('city')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="zip_code">Postal/ZIP Code</label>
                    <input type="text" id="zip_code" name="zip_code" value="{{ $userDetails->zip_code ?? old('zip_code') }}" required>
                    @error('zip_code')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state" value="{{ $userDetails->state ?? old('state') }}" required>
                    @error('state')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="{{ $userDetails->country ?? old('country') }}" required>
                    @error('country')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h2>Payment Information</h2>

            <div class="form-group">
                <label for="cardholder_name">Cardholder Name</label>
                <input type="text" id="cardholder_name" name="cardholder_name" value="{{ $creditCard->cardholder_name ?? old('cardholder_name') }}" required>
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
                    <label for="expiration_month">Expiration Month</label>
                    <input type="number" id="expiration_month" name="expiration_month" min="1" max="12" placeholder="MM" value="{{ $creditCard->expiration_month ?? old('expiration_month') }}" required>
                    @error('expiration_month')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="expiration_year">Expiration Year</label>
                    <input type="number" id="expiration_year" name="expiration_year" min="{{ date('Y') }}" max="{{ date('Y') + 10 }}" placeholder="YYYY" value="{{ $creditCard->expiration_year ?? old('expiration_year') }}" required>
                    @error('expiration_year')
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

            <button type="submit" class="submit-button">Complete Payment</button>
        </form>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

    <script src="{{asset('js/payment-validation.js')}}"></script>
</body>
</html>
