
document.addEventListener('DOMContentLoaded', function () {

    // edit button functionality
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('.info-row');
            const originalValue = row.querySelector('.info-value').textContent.trim();

            row.dataset.originalValue = originalValue;
            row.querySelector('.info-value').style.display = 'none';
            const input = row.querySelector('.edit-input');
            input.value = originalValue;
            input.style.display = 'inline-block';
            row.querySelector('.edit-btn').style.display = 'none';
            row.querySelector('.save-btn').style.display = 'inline-block';
            row.querySelector('.cancel-btn').style.display = 'inline-block';
        });
    });

    // save button functionality
    document.querySelectorAll('.save-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('.info-row');
            const field = row.dataset.field;
            const newValue = row.querySelector('.edit-input').value;
            let dataToSend = {};

            if (field === 'card_expire')
            {
                if (!/^\d{4}-\d{2}$/.test(newValue))
                {
                    alert('Invalid date format. Use YYYY-MM.');
                    return;
                }

                const [year, month] = newValue.split('-');

                dataToSend = {
                    expiration_year: year,
                    expiration_month: month
                };
            }
            else if (field === 'password')
            {
                if (newValue.length < 8)
                {
                    alert('Password must be at least 8 characters.');
                    return;
                }

                dataToSend = { password: newValue };
            }
            else
            {
                dataToSend[field] = newValue;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/account/update', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(dataToSend)
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        if (field === 'card_expire') {
                            document.getElementById('card_expire-value').textContent = `${dataToSend.expiration_year}-${String(dataToSend.expiration_month).padStart(2, '0')}`;
                        } else if (field === 'password') {
                            document.getElementById('password-value').textContent = '********';
                        } else {
                            document.getElementById(`${field}-value`).textContent = newValue;
                        }

                        row.querySelector('.info-value').style.display = 'inline-block';
                        row.querySelector('.edit-input').style.display = 'none';
                        row.querySelector('.edit-btn').style.display = 'inline-block';
                        row.querySelector('.save-btn').style.display = 'none';
                        row.querySelector('.cancel-btn').style.display = 'none';
                    }
                    else
                    {
                        alert('Updated failed!');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Updated failed!');
                });
        });
    });

    // cancel button functionality
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('.info-row');
            const originalValue = row.dataset.originalValue;

            const input = row.querySelector('.edit-input');
            input.value = originalValue;
            input.style.display = 'none';

            row.querySelector('.info-value').style.display = 'inline-block';
            row.querySelector('.save-btn').style.display = 'none';
            row.querySelector('.cancel-btn').style.display = 'none';
            row.querySelector('.edit-btn').style.display = 'inline-block';
        });
    });
});
