document.addEventListener('DOMContentLoaded', function()  {
    document.getElementById('qty').addEventListener('change', event => {
        const val = event.target.value;
        if (val < 1 || isNaN(val))
            event.target.value = 1;
    });

    document.getElementById('add_to_cart_button').addEventListener('click', function() {
        let qtyElement = document.getElementById('qty');

        const productID = qtyElement.getAttribute('data-productid').valueOf();
        const qty = qtyElement.value;

        fetch(`/cart/add/${productID}/${qty}`)
        .then(response => response.json())
        .then(json => {
            alert(json['msg']);
        })
        .catch(error => {
            console.log(error);
            alert('Error occurred.');
        });
    });
});
