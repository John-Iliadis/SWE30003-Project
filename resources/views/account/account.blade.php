<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account</title>

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body data-account-update-url="{{ route('account.update') }}">
    @include('partials.header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div class="account-container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1 id="title">My Account</h1>
                <a href="/history" class="edit-btn">View Order History</a>
            </div>

            <div class="account-section">
                <h2>Personal Information</h2>
                <div class="heading-line"></div>

                <div class="info-row" data-field="name">
                    <span class="info-label">Name:</span>
                    <span class="info-value" id="name-value">{{ $user_details['name'] }}</span>
                    <input class="edit-input" id="name-input" type="text" value="{{ $user_details['name'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="email">
                    <span class="info-label">Email:</span>
                    <span class="info-value" id="email-value">{{ $user_details['email'] }}</span>
                    <input class="edit-input" id="email-input" type="email" value="{{ $user_details['email'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="password">
                    <span class="info-label">Password:</span>
                    <span class="info-value" id="password-value">********</span>
                    <input class="edit-input" id="password-input" type="password" placeholder="New password" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="phone_number">
                    <span class="info-label">Phone:</span>
                    <span class="info-value" id="phone_number-value">{{ $user_details['phone_number'] }}</span>
                    <input class="edit-input" id="phone_number-input" type="text" value="{{ $user_details['phone_number'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>
            </div>

            <div class="account-section">
                <h2>Address Information</h2>
                <div class="heading-line"></div>

                <div class="info-row" data-field="address">
                    <span class="info-label">Address:</span>
                    <span class="info-value" id="address-value">{{ $user_details['address'] }}</span>
                    <input class="edit-input" id="address-input" type="text" value="{{ $user_details['address'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="city">
                    <span class="info-label">City:</span>
                    <span class="info-value" id="city-value">{{ $user_details['city'] }}</span>
                    <input class="edit-input" id="city-input" type="text" value="{{ $user_details['city'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="post_code">
                    <span class="info-label">Post Code:</span>
                    <span class="info-value" id="zip_code-value">{{ $user_details['zip_code'] }}</span>
                    <input class="edit-input" id="zip_code-input" type="text" value="{{ $user_details['zip_code'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="state">
                    <span class="info-label">State:</span>
                    <span class="info-value" id="state-value">{{ $user_details['state'] }}</span>
                    <input class="edit-input" id="state-input" type="text" value="{{ $user_details['state'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="country">
                    <span class="info-label">Country:</span>
                    <span class="info-value" id="country-value">{{ $user_details['country'] }}</span>
                    <input class="edit-input" id="country-input" type="text" value="{{ $user_details['country'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>
            </div>

            <div class="account-section">
                <h2>Payment Information</h2>
                <div class="heading-line"></div>

                <div class="info-row" data-field="card_holder">
                    <span class="info-label">Card Holder:</span>
                    <span class="info-value" id="card_holder-value">{{ $card_details['cardholder_name'] }}</span>
                    <input class="edit-input" id="card_holder-input" type="text" value="{{ $card_details['cardholder_name'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="card_number">
                    <span class="info-label">Card Number:</span>
                    <span class="info-value" id="card_number-value">{{ $card_details['card_number'] }}</span>
                    <input class="edit-input" id="card_number-input" type="text"
                           value="{{ $card_details['card_number'] }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="card_expire">
                    <span class="info-label">Expires:</span>
                    <span class="info-value" id="card_expire-value">
                {{ $card_details['expiration_year'] }}-{{ str_pad($card_details['expiration_month'], 2, '0', STR_PAD_LEFT) }}
            </span>

                    <input class="edit-input" id="card_expire-input" type="month"
                           value="{{ $card_details['expiration_year'] }}-{{ str_pad($card_details['expiration_month'], 2, '0', STR_PAD_LEFT) }}"
                           style="display:none;">

                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
                @csrf
                <button type="submit" class="logout-button">Log Out</button>
            </form>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/account.js') }}"></script>

</body>
</html>
