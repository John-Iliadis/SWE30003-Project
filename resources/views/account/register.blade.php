<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            <h2>Register</h2>
            @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="form-section">
                    <h3 class="section-heading">Personal Information</h3>
                    <div class="heading-line"></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" placeholder="Full name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" placeholder="Email address" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" placeholder="Create password" value="{{ old('password') }}" required>
                            <p class="password-hint">The password must contain min 8 characters</p>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input id="phone_number" name="phone_number" type="tel" placeholder="1234 567 890" value="{{ old('phone_number') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-heading">Address Information</h3>
                    <div class="heading-line"></div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input id="address" name="address" type="text" placeholder="Street address" value="{{ old('address') }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input id="city" name="city" type="text" placeholder="City" value="{{ old('city') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="post_code">Post Code</label>
                            <input id="post_code" name="post_code" type="text" placeholder="1234" value="{{ old('post_code') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input id="state" name="state" type="text" placeholder="State/Province" value="{{ old('state') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input id="country" name="country" type="text" placeholder="Country" value="{{ old('country') }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-heading">Payment Information</h3>
                    <div class="heading-line"></div>

                    <div class="form-group">
                        <label for="card_holder">Card Holder Name</label>
                        <input id="card_holder" name="card_holder" type="text" placeholder="Name on card" value="{{ old('card_holder') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="card_number">Card Number</label>
                        <input id="card_number" name="card_number" type="text" placeholder="1234 5678 9012 3456" value="{{ old('card_number') }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_expire">Expiry Date</label>
                            <input id="card_expire" name="card_expire" type="month" value="{{ old('card_expire') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="card_cvv">CVV</label>
                            <input id="card_cvv" name="card_cvv" type="number" placeholder="123" value="{{ old('cvv') }}" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="submit-button">Create Account</button>
            </form>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')
</body>
</html>
