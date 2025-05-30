function updateTotalPrice(total)
{
    document.getElementById('total_price').textContent = 'Total: $' + parseInt(total);
}

function checkIfCartIsEmpty()
{
    if (document.querySelectorAll('.cart_listing').length === 0)
    {
        document.querySelector('main').innerHTML = `
                <div id="empty_cart_container">
                    <h1>Your shopping cart is empty</h1>
                    <p>Check out some of our most popular products below, or feel free to browse!</p>
                    <a href="/catalogue">
                        <button id="start_shopping_button">Start Shopping</button>
                    </a>
                </div>
            `;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    // Add event listener for checkout button
    const checkoutButton = document.getElementById('checkout_button');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            window.location.href = '/payment';
        });
    }

    const removeButtons = document.querySelectorAll('.remove_item');

    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-productId');
            const cartItem = this.closest('.cart_listing');

            fetch(`/cart/remove/${productId}`)
                .then(response => response.json())
                .then(data => {
                        cartItem.remove();
                        updateTotalPrice(data.total);
                        checkIfCartIsEmpty();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error occurred.');
                });
        });
    });

    document.getElementById('clear_cart_button').addEventListener('click', function () {
        fetch(`/cart/clear`)
        .then(() => {
            document.querySelectorAll('.cart_listing').forEach(el => {
                el.remove();
            });

            checkIfCartIsEmpty();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error occurred.');
        })
    });

    document.querySelectorAll('.qty_num_input').forEach(input => {
        input.addEventListener('change', function () {
            const productID = input.closest('.cart_listing')
                .getAttribute('data-productid')
                .valueOf();

            let qty = input.value;

            if (qty < 1 || isNaN(qty))
            {
                input.value = 1;
                qty = 1;
            }

            fetch(`/cart/modify/${productID}/${qty}`)
            .then(response => response.json())
            .then(data => {
                input.closest('.cart_listing').querySelector('.cart_total_price').innerText = '$' + data['subtotal'];
                document.querySelector('#total_price').textContent = 'Total: $' + data['total'];
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error occurred.');
            });
        });
    });
});
