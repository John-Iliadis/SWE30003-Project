// Payment form validation script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.payment-form');

    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Email validation
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError(email, 'Please enter a valid email address');
                isValid = false;
            } else {
                clearError(email);
            }

            // Phone validation
            const phone = document.getElementById('phone_number');
            const phoneRegex = /^[0-9\s]{8,12}$/;
            if (!phoneRegex.test(phone.value)) {
                showError(phone, 'Please enter a valid phone number (8-12 digits)');
                isValid = false;
            } else {
                clearError(phone);
            }

            // Card number validation
            const cardNumber = document.getElementById('card_number');
            const cardRegex = /^[0-9]{16}$/;
            if (!cardRegex.test(cardNumber.value.replace(/\s/g, ''))) {
                showError(cardNumber, 'Please enter a valid 16-digit card number');
                isValid = false;
            } else {
                clearError(cardNumber);
            }

            // CVV validation
            const cvv = document.getElementById('cvv');
            const cvvRegex = /^[0-9]{3,4}$/;
            if (!cvvRegex.test(cvv.value)) {
                showError(cvv, 'Please enter a valid 3 or 4 digit CVV');
                isValid = false;
            } else {
                clearError(cvv);
            }

            // Name validation
            const name = document.getElementById('name');
            if (name.value.trim() === '') {
                showError(name, 'Please enter your full name');
                isValid = false;
            } else {
                clearError(name);
            }

            // Address validation
            const address = document.getElementById('address');
            if (address.value.trim() === '') {
                showError(address, 'Please enter your delivery address');
                isValid = false;
            } else {
                clearError(address);
            }

            // City validation
            const city = document.getElementById('city');
            if (city.value.trim() === '') {
                showError(city, 'Please enter your city');
                isValid = false;
            } else {
                clearError(city);
            }

            // ZIP code validation
            const zipCode = document.getElementById('zip_code');
            if (zipCode.value.trim() === '') {
                showError(zipCode, 'Please enter your postal/ZIP code');
                isValid = false;
            } else {
                clearError(zipCode);
            }

            // State validation
            const state = document.getElementById('state');
            if (state.value.trim() === '') {
                showError(state, 'Please enter your state/province');
                isValid = false;
            } else {
                clearError(state);
            }

            // Country validation
            const country = document.getElementById('country');
            if (country.value.trim() === '') {
                showError(country, 'Please enter your country');
                isValid = false;
            } else {
                clearError(country);
            }

            // Cardholder name validation
            const cardholderName = document.getElementById('cardholder_name');
            if (cardholderName.value.trim() === '') {
                showError(cardholderName, 'Please enter the cardholder name');
                isValid = false;
            } else {
                clearError(cardholderName);
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }

    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        let error = formGroup.querySelector('.error');

        if (!error) {
            error = document.createElement('div');
            error.className = 'error';
            formGroup.appendChild(error);
        }

        error.textContent = message;
        input.classList.add('input-error');
    }

    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const error = formGroup.querySelector('.error');

        if (error) {
            error.textContent = '';
        }

        input.classList.remove('input-error');
    }

    // Format card number as user types
    const cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            // Remove any non-digit characters
            let value = this.value.replace(/\D/g, '');

            // Limit to 16 digits
            if (value.length > 16) {
                value = value.slice(0, 16);
            }

            this.value = value;
        });
    }
});
