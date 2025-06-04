
$(document).ready(function() {
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
            url: document.body.dataset.accountUpdateUrl,
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
            error: function(xhr) {
                console.error(xhr.responseText);
                toastr.error(xhr.responseJSON?.message || 'Error');
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
});