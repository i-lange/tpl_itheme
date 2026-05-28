{
    document.querySelectorAll('input[name="shipping"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.checkout-shipping-params').forEach(params => {
                params.classList.remove('active');
            });

            if (radio.checked) {
                document.getElementById('toogle-' + this.id).classList.add('active');
            }
        });
    });

    function clearCart() {
        const url = '/index.php?option=com_ishop&controller=cart&task=remove';
        const request = new XMLHttpRequest();
        request.open('POST', url, true);
        request.send();

        document.querySelectorAll('[data-ishop-cart]').forEach(cart => {
            cart.querySelector('small').textContent = 0;
        });
    }


    // отправка формы заказа
    const checkout_form = document.getElementById('checkout-submit');
    if (!!checkout_form) {
        checkout_form.addEventListener('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const button = checkout_form.querySelector('[type="submit"]');
            const tokens = checkout_form.querySelectorAll('[value="1"]');
            let jtoken = '';
            const form_data = new FormData(checkout_form);

            //ищем токен формы
            tokens.forEach(function (input) {
                if (input.type === 'hidden' && input.name.length === 32) {
                    jtoken = input.name;
                }
            });

            // создаем иконку загрузки
            const spinner = document.createElement('i');
            spinner.classList.add('spinner');
            button.appendChild(spinner);

            // если поля заполнены верно
            if (!window.iTheme.Validate(checkout_form)) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '?option=com_ajax&module=iform&format=json', true);
                xhr.setRequestHeader('X-CSRF-Token', jtoken);
                xhr.setRequestHeader('Cache-Control', 'no-cache');

                xhr.addEventListener("readystatechange", () => {
                    if (xhr.readyState !== XMLHttpRequest.DONE) {
                        return;
                    }

                    if (xhr.status === 200) {
                        //console.log(xhr.responseText);
                        const data = JSON.parse(xhr.responseText);
                        //console.log(data);
                        checkoutUpdate(checkout_form, data);

                        if (data.data.errors.length === 0) {
                            try {
                                gtag("event", "purchase", {
                                    transaction_id: Date.now().toString(16),
                                    value: form_data.get('total'),
                                    shipping: 0,
                                    currency: 'BYN',
                                    items: dataLayerItems
                                });
                            } catch (e) {
                                console.log('Error goal[ORDER]');
                            }
                            checkout_form.reset();
                            checkout_form.classList.add('d-none');
                            clearCart();
                            document.querySelector('.checkout_thank_you').classList.add('active');
                        }
                    }

                    setTimeout(() => {
                        spinner.remove();
                    }, 300);
                });
                xhr.send(form_data);
            }
        });
    }
}

// Обновляем статусы полей в форме
function checkoutUpdate(form, data) {
    if (data.data.errors.length !== 0) {
        data.data.errors.forEach(function (error) {
            if (error.key !== 'header') {
                const input = form.querySelector('[name="' + error.key + '"]');
                input.classList.add('is-invalid');
            }
        });
    }

    if (data.data.valid.length !== 0) {
        data.data.valid.forEach(function (valid) {
            const input = form.querySelector('[name="' + valid + '"]');
            input.classList.add('is-valid');
        });
    }
}
