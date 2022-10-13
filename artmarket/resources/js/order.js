const checkoutForm = document.getElementById('form-checkout');

window.calculateTotal = function () {
    let input = new FormData(checkoutForm);
    input.append('calculate', true)
    query(checkoutForm.action, checkoutForm.method, input)
        .then((response) => {
            let order = response.data;
            if (order) {
                document.getElementById("price-total").innerHTML = order.total.formatted;
            }
        })
}

if (checkoutForm !== null) {
    checkoutForm.addEventListener('change', function () {
        calculateTotal();
    });
}

