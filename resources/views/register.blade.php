<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
</head>

<body>
    @include('header')

    <div class="register_container">
        <h2>Register</h2>
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="section-heading">Personal Information</h3>
                <div class="heading-line"></div>
                <div class="personal-group">
                    <div class="personal-field">
                        <label class="register_form label">Name:</label>
                        <input name="name" type="text" placeholder="Full name">
                    </div>
                    <div class="personal-field">
                        <label>Email:</label>
                        <input name="email" type="email" placeholder="Email address">
                    </div>
                    <div class="personal-field">
                        <label>Password:</label>
                        <input name="password" type="password" placeholder="Create password">
                        <p class="password-hint">The password must contain min 8 characters</p>
                    </div>
                    <div class="personal-field">
                        <label>Phone Number:</label>
                        <input name="phone_number" type="tel" placeholder="1234 567 890">
                    </div>
                </div>
            </div>
            
            <!-- Address Information Section -->
            <div class="form-section">
                <h3 class="section-heading">Address Information</h3>
                <div class="heading-line"></div>
                <div class="address-field">
                    <label>Address:</label>
                    <input name="address" type="text" placeholder="Street address"><br>
                </div>
                <div class="address-group">
                    <div class="address-field">
                        <label>City:</label>
                        <input name="city" type="text" placeholder="City">
                    </div>
                    <div class="address-field">
                        <label>Post Code:</label>
                        <input name="post_code" type="text" placeholder="1234">
                    </div>
                    <div class="address-field">
                        <label>State:</label>
                        <input name="state" type="text" placeholder="State/Province">
                    </div>
                    <div class="address-field">
                        <label>Country:</label>
                        <input name="country" type="text" placeholder="Country">
                    </div>
            </div>
            
            <!-- Payment Information Section -->
            <div class="form-section">
                <h3 class="section-heading">Payment Information</h3>
                <div class="heading-line"></div>
                
                <div class="card-details">
                    <div class="card-field">
                        <label>Card Holder Name:</label>
                        <input name="card_holder" type="text" placeholder="Name on card">
                    </div>
                    <div class="card-field">
                        <label>Card Number:</label>
                        <input name="card_number" type="text" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="card-field">
                        <label>Expiry Date:</label>
                        <input name="card_expire" type="month">
                    </div>
                    <div class="card-field">
                        <label>CVV:</label>
                        <input name="card_cvv" type="number" placeholder="123">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="register-button">Create Account</button>
        </form>
    </div>

    @include('footer')
</body>
</html>