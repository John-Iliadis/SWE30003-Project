function getFilterData()
{
    const filterData = {};

    const radioData = document.querySelectorAll('#filter input[type="radio"]');
    radioData.forEach(radio => {
        if (radio.checked)
        {
            filterData[radio.name] = radio.value;
        }
    });

    const checkBoxData = document.querySelectorAll('#filter input[type="checkbox"]');
    checkBoxData.forEach(checkbox => {
        if (checkbox.checked)
        {
            if (!filterData[checkbox.name])
            {
                filterData[checkbox.name] = [];
            }

            filterData[checkbox.name].push(checkbox.value);
        }
    });

    return filterData;
}

function addToCartListeners()
{
    document.querySelectorAll('.add_to_cart_button').forEach(button => {
        button.addEventListener('click', (e) => {
            const productID = e.currentTarget.dataset.productid;
            const qty = 1;

            fetch(`/cart/add/${productID}/${qty}`)
                .then(response => response.json())
                .then(json => {alert(json['msg'])})
                .catch(error => {console.log(error);});
        });
    });
}

function updateCatalogue()
{
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/catalogue/filter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify(getFilterData())
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error: ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('catalogue').innerHTML = html;
            addToCartListeners();
        })
        .catch(error => {
            console.error(error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    let filterVals = document.getElementsByClassName('filter_val')
    for (let filterVal of filterVals)
    {
        filterVal.addEventListener('change', updateCatalogue);
    }

    addToCartListeners();

    document.getElementById('reset_button').addEventListener('click', function() {
        let update = false;

        document.querySelectorAll('#filter .filter_val').forEach(function(input) {
            if (input.checked !== input.defaultChecked)
            {
                input.checked = input.defaultChecked;
                update = true;
            }
        });

        if (update)
        {
            updateCatalogue();
        }
    });

    document.getElementById('price_set_button').addEventListener('click', function() {
        const minInput = document.querySelector("input[name='min_price']");
        const maxInput = document.querySelector("input[name='max_price']");

        let min = minInput.value;
        let max = maxInput.value;

        if (min === "" || max === "")
        {
            alert('Please enter a valid price.');
            return;
        }

        min = parseFloat(min);
        max = parseFloat(max);

        if (max < min)
        {
            alert('Max needs to be greater or equal than min.');
            return;
        }

        const newValue = `${min} ${max}`;

        // Check if checkbox with same value already exists
        const existingCheckboxes = document.querySelectorAll("input[name='price_range']");
        for (let checkbox of existingCheckboxes)
        {
            if (checkbox.value === newValue) {
                alert("This price range already exists.");
                return;
            }
        }

        const label = document.createElement("label");
        label.className = "filter_checkbox";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "filter_val";
        checkbox.name = "price_range";
        checkbox.value = `${min} ${max}`;
        checkbox.checked = true;
        checkbox.addEventListener('change', updateCatalogue);

        const span = document.createElement("span");
        span.className = "checkbox_title";
        span.textContent = `$${min} - $${max}`;

        label.appendChild(checkbox);
        label.appendChild(span);

        const filterLine = document.getElementById("filter_line");
        filterLine.parentNode.insertBefore(label, filterLine);

        minInput.value = '';
        maxInput.value = '';

        updateCatalogue();
    });
});
