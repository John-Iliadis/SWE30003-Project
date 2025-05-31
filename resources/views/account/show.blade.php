<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    @include('header')

    <div id="spacing_top" style="height: 80px"></div>

    <main>
        <div class="account-container">
            <h1 id="title">My Account</h1>

            <div class="account-section">
                <h2>Personal Information</h2>
                <div class="heading-line"></div>

                <div class="info-row" data-field="name">
                    <span class="info-label">Name:</span>
                    <span class="info-value" id="name-value">{{ $customer->name }}</span>
                    <input class="edit-input" id="name-input" type="text" value="{{ $customer->name }}" style="display:none;">
                    <button class="edit-btn">Edit</button>
                    <button class="save-btn" style="display:none;">Save</button>
                    <button class="cancel-btn" style="display:none;">Cancel</button>
                </div>

                <div class="info-row" data-field="email">
                    <span class="info-label">Email:</span>
                    <span class="info-value" id="email-value">{{ $customer->email }}</span>
                    <input class="edit-input" id="email-input" type="email" value="{{ $customer->email }}" style="display:none;">
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

                @if($customer->details)
                    <div class="info-row" data-field="phone_number">
                        <span class="info-label">Phone:</span>
                        <span class="info-value" id="phone_number-value">{{ $customer->details->phone_number }}</span>
                        <input class="edit-input" id="phone_number-input" type="text" value="{{ $customer->details->phone_number }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>
                @endif
            </div>

            @if($customer->details)
                <div class="account-section">
                    <h2>Address Information</h2>
                    <div class="heading-line"></div>

                    <div class="info-row" data-field="address">
                        <span class="info-label">Address:</span>
                        <span class="info-value" id="address-value">{{ $customer->details->address }}</span>
                        <input class="edit-input" id="address-input" type="text" value="{{ $customer->details->address }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>

                    <div class="info-row" data-field="city">
                        <span class="info-label">City:</span>
                        <span class="info-value" id="city-value">{{ $customer->details->city }}</span>
                        <input class="edit-input" id="city-input" type="text" value="{{ $customer->details->city }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>

                    <div class="info-row" data-field="post_code">
                        <span class="info-label">Post Code:</span>
                        <span class="info-value" id="zip_code-value">{{ $customer->details->zip_code }}</span>
                        <input class="edit-input" id="zip_code-input" type="text" value="{{ $customer->details->zip_code }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>

                    <div class="info-row" data-field="state">
                        <span class="info-label">State:</span>
                        <span class="info-value" id="state-value">{{ $customer->details->state }}</span>
                        <input class="edit-input" id="state-input" type="text" value="{{ $customer->details->state }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>

                    <div class="info-row" data-field="country">
                        <span class="info-label">Country:</span>
                        <span class="info-value" id="country-value">{{ $customer->details->country }}</span>
                        <input class="edit-input" id="country-input" type="text" value="{{ $customer->details->country }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>
                </div>
            @endif

            @if($customer->creditCard)
                <div class="account-section">
                    <h2>Payment Information</h2>
                    <div class="heading-line"></div>

                    <div class="info-row" data-field="card_holder">
                        <span class="info-label">Card Holder:</span>
                        <span class="info-value" id="card_holder-value">{{ $customer->creditCard->cardholder_name }}</span>
                        <input class="edit-input" id="card_holder-input" type="text" value="{{ $customer->creditCard->cardholder_name }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>

                    <div class="info-row" data-field="card_number">
                        <span class="info-label">Card Number:</span>
                        <span class="info-value" id="card_number-value">{{ $customer->creditCard->card_number }}</span>
                        <input class="edit-input" id="card_number-input" type="text"
                               value="{{ $customer->creditCard->card_number }}" style="display:none;">
                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>

                    <div class="info-row" data-field="card_expire">
                        <span class="info-label">Expires:</span>
                        <span class="info-value" id="card_expire-value">
                    {{ $customer->creditCard->expiration_year }}-{{ str_pad($customer->creditCard->expiration_month, 2, '0', STR_PAD_LEFT) }}
                </span>

                        <input class="edit-input" id="card_expire-input" type="month"
                               value="{{ $customer->creditCard->expiration_year }}-{{ str_pad($customer->creditCard->expiration_month, 2, '0', STR_PAD_LEFT) }}"
                               style="display:none;">

                        <button class="edit-btn">Edit</button>
                        <button class="save-btn" style="display:none;">Save</button>
                        <button class="cancel-btn" style="display:none;">Cancel</button>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
                @csrf
                <button type="submit" class="logout-button">Log Out</button>
            </form>
        </div>
    </main>

    <div id="spacing_bottom" style="height: 80px"></div>

    @include('footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $('.edit-btn').on('click', function () {
            const row = $(this).closest('.info-row');
            const field = row.data('field');
            const originalValue = row.find('.info-value').text().trim();

            row.data('original-value', originalValue);
            row.find('.info-value').hide();
            row.find('.edit-input').val(originalValue).show();
            row.find('.edit-btn').hide();
            row.find('.save-btn, .cancel-btn').show();
        });

        $('.save-btn').on('click', function () {
            const row = $(this).closest('.info-row');
            const field = row.data('field');
            const newValue = row.find('.edit-input').val();
            let dataToSend = {};

            if (field === 'card_expire') {
                if (!/^\d{4}-\d{2}$/.test(newValue)) {
                    toastr.error('Invalid date format. Use YYYY-MM.');
                    return;
                }
                const [year, month] = newValue.split('-');
                dataToSend = {
                    expiration_year: year,
                    expiration_month: month
                };
            }

            else if (field === 'password') {
                if (newValue.length < 8) {
                    toastr.error('Password must be at least 8 characters.');
                    return;
                }
                dataToSend = { password: newValue };
            }

            else {
                dataToSend = { [field]: newValue };
            }

            $.ajax({
                url: '{{ route("account.update") }}',
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                data: dataToSend,
                success: function (response) {
                    if (response.success) {
                        if (field === 'card_expire') {
                            $('#card_expire-value').text(`${dataToSend.expiration_year}-${String(dataToSend.expiration_month).padStart(2, '0')}`);
                        } else if (field === 'password') {
                            $('#password-value').text('********');
                        } else {
                            $(`#${field}-value`).text(newValue);
                        }

                        row.find('.info-value').show();
                        row.find('.edit-input').hide();
                        row.find('.edit-btn').show();
                        row.find('.save-btn, .cancel-btn').hide();
                        toastr.success('Updated successfully!');
                    } else {
                        toastr.error('Update failed.');
                    }
                },
                error: function () {
                    toastr.error('Something went wrong.');
                }
            });
        });


        $('.cancel-btn').on('click', function () {
            const row = $(this).closest('.info-row');
            const originalValue = row.data('original-value');

            row.find('.edit-input').val(originalValue).hide();
            row.find('.info-value').show();
            row.find('.save-btn, .cancel-btn').hide();
            row.find('.edit-btn').show();
        });
    </script>

</body>
</html>
