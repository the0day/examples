window.query = async function (url = '', method = 'POST', formData = new FormData) {
    const response = await fetch(url, {
        method: method,
        //mode: 'cors',
        cache: 'no-cache',
        //credentials: 'same-origin',
        dataType: 'JSON',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        redirect: 'follow',
        //referrerPolicy: 'no-referrer',
        body: JSON.stringify(serializeForm(formData))
    }).then((res) => {
        if (res.redirected) {
            console.log('redirect');
            document.location = res.url;
        }

        return res;
    });

    return await response.json();
}

function update(data, keys, value) {
    if (keys.length === 0) {
        // Leaf node
        return value;
    }

    let key = keys.shift();
    if (!key) {
        data = data || [];
        if (Array.isArray(data)) {
            key = data.length;
        }
    }

    // Try converting key to a numeric value
    let index = +key;
    if (!isNaN(index)) {
        // We have a numeric index, make data a numeric array
        // This will not work if this is a associative array
        // with numeric keys
        data = data || [];

        key = index;
    }

    // If none of the above matched, we have an associative array
    data = data || {};
    key = key.toString().replace(/(^'|'$)/g, '');
    let val = update(data[key], keys, value);
    data[key] = val;

    return data;
}

function serializeForm(form) {
    return Array.from((form).entries())
        .reduce((data, [field, value]) => {
            let [_, prefix, keys] = field.match(/^([^\[]+)((?:\[[^\]]*\])*)/);

            if (keys) {
                keys = Array.from(keys.matchAll(/\[([^\]]*)\]/g), m => m[1]);
                value = update(data[prefix], keys, value);
            }
            data[prefix] = value;
            return data;
        }, {});
}


window.submitForm = function (el, btn) {
    let form = document.getElementById(el);

    const oldTitle = btn.innerHTML;
    btn.innerHTML = 'Loading';
    btn.disabled = true;
    resetClassesForm(form);

    query(form.action, form.method, new FormData(form))
        .then((response) => {
            if (response.errors) {
                handleResponse(form, response);

            }
        })
        .catch((res) => handleResponse(form, res))
        .finally(() => {
            btn.innerHTML = oldTitle;
            btn.disabled = false;
        })

    function resetClassesForm(form) {
        let fields = form.querySelectorAll("input, select");
        [].forEach.call(fields, function (el) {
            el.classList.remove("border-red-600");
        });
    }

    function handleResponse(form, res) {
        if (res.errors) {
            for (const [field, data] of Object.entries(res.errors)) {
                form.querySelector('[name="' + field + '"]').classList.add("border-red-600")

                data.forEach(function (msg) {
                    new Message().error(msg);
                });
            }
        }
    }
}

import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

Array.from(document.getElementsByClassName('datepicker')).forEach(
    (element, index, array) => {
        let minDate = element.getAttribute('data-min-date');

        let options = {
            'autohide': true,
            'format': 'dd/mm/yyyy'
        };

        if (minDate) {
            options.minDate = minDate;
        }

        new Datepicker(element, options);
    }
)


