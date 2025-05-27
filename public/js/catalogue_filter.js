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

function updateCatalogue()
{
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let xhr = new XMLHttpRequest();

    xhr.open('post', '/catalogue/filter', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-CSRF-TOKEN', token);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4)
        {
            if (xhr.status === 200)
            {
                document.getElementById('catalogue').innerHTML = xhr.responseText;
            }
            else
            {
                console.error('Error:', xhr.statusText);
            }
        }
    }

    xhr.send(JSON.stringify(getFilterData()));
}

document.addEventListener('DOMContentLoaded', function() {
    let filterVals = document.getElementsByClassName('filter_val');

    for (let filterVal of filterVals)
    {
        filterVal.addEventListener('change', updateCatalogue);
    }

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
            console.log(update);
            updateCatalogue();
        }
    });
});
