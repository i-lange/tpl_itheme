(function () {
    'use strict';

    var SELECTORS = {
        shippingRadios: 'input[name="shipping"]',
        shippingParams: '.checkout-shipping-params',
        cart: '[data-ishop-cart]',
        form: '#checkout-submit',
        thankYou: '.checkout_thank_you'
    };

    function onReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback, { once: true });
        } else {
            callback();
        }
    }

    function waitFor(condition, callback, options) {
        options = options || {};
        var timeout = options.timeout || 10000;
        var interval = options.interval || 100;
        var startedAt = Date.now();

        function check() {
            var result = false;

            try {
                result = condition();
            } catch (e) {}

            if (result) {
                callback(result);
                return;
            }

            if (Date.now() - startedAt >= timeout) {
                return;
            }

            setTimeout(check, interval);
        }

        check();
    }

    function clearCart() {
        var url = '/index.php?option=com_ishop&controller=cart&task=remove';
        var request = new XMLHttpRequest();

        request.open('POST', url, true);
        request.send();

        document.querySelectorAll(SELECTORS.cart).forEach(function (cart) {
            var small = cart.querySelector('small');
            if (small) {
                small.textContent = '0';
            }
        });
    }

    function updateShippingState(activeRadio) {
        document.querySelectorAll(SELECTORS.shippingParams).forEach(function (params) {
            params.classList.remove('active');
        });

        if (!activeRadio || !activeRadio.checked) {
            return;
        }

        var toggleBlock = document.getElementById('toogle-' + activeRadio.id);
        if (toggleBlock) {
            toggleBlock.classList.add('active');
        }
    }

    function bindShipping() {
        var radios = document.querySelectorAll(SELECTORS.shippingRadios);
        if (!radios.length) {
            return;
        }

        radios.forEach(function (radio) {
            if (radio.dataset.checkoutShippingBound === '1') {
                return;
            }

            radio.dataset.checkoutShippingBound = '1';

            radio.addEventListener('change', function () {
                updateShippingState(radio);
            });

            if (radio.checked) {
                updateShippingState(radio);
            }
        });
    }

    function checkoutUpdate(form, data) {
        if (!data || !data.data) {
            return;
        }

        var errors = Array.isArray(data.data.errors) ? data.data.errors : [];
        var valid = Array.isArray(data.data.valid) ? data.data.valid : [];

        valid.forEach(function (fieldName) {
            var validInput = form.querySelector('[name="' + fieldName + '"]');
            if (validInput) {
                validInput.classList.remove('is-invalid');
                validInput.classList.add('is-valid');
            }
        });

        errors.forEach(function (error) {
            if (!error || error.key === 'header') {
                return;
            }

            var invalidInput = form.querySelector('[name="' + error.key + '"]');
            if (invalidInput) {
                invalidInput.classList.remove('is-valid');
                invalidInput.classList.add('is-invalid');
            }
        });
    }

    function getCsrfTokenName(form) {
        var inputs = form.querySelectorAll('input[type="hidden"][value="1"]');
        var tokenName = '';

        inputs.forEach(function (input) {
            if (input.name && input.name.length === 32) {
                tokenName = input.name;
            }
        });

        return tokenName;
    }

    function setSubmitButtonLoading(button, isLoading) {
        if (!button) {
            return;
        }

        if (isLoading) {
            if (button.dataset.loading === '1') {
                return;
            }

            button.dataset.loading = '1';
            button.dataset.wasDisabled = button.disabled ? '1' : '0';
            button.dataset.hadAriaBusy = button.hasAttribute('aria-busy') ? '1' : '0';
            button.dataset.previousAriaBusy = button.getAttribute('aria-busy') || '';
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');

            if (!button.querySelector('[data-submit-spinner="1"]')) {
                var spinner = document.createElement('i');
                spinner.className = 'spinner spinner-border spinner-border-sm ms-2';
                spinner.setAttribute('aria-hidden', 'true');
                spinner.dataset.submitSpinner = '1';
                button.appendChild(spinner);
            }

            return;
        }

        button.dataset.loading = '0';
        button.querySelectorAll('[data-submit-spinner="1"]').forEach(function (spinner) {
            spinner.remove();
        });

        button.disabled = button.dataset.wasDisabled === '1';

        if (button.dataset.hadAriaBusy === '1') {
            button.setAttribute('aria-busy', button.dataset.previousAriaBusy || '');
        } else {
            button.removeAttribute('aria-busy');
        }

        delete button.dataset.wasDisabled;
        delete button.dataset.hadAriaBusy;
        delete button.dataset.previousAriaBusy;
    }

    function submitCheckout(form, event) {
        event.preventDefault();
        event.stopPropagation();

        var button = form.querySelector('[type="submit"]');
        if (!button) {
            return;
        }

        if (!window.iTheme || typeof window.iTheme.Validate !== 'function') {
            console.error('iTheme.Validate is not available');
            return;
        }

        var hasErrors = window.iTheme.Validate(form);
        if (hasErrors) {
            return;
        }

        if (button.dataset.loading === '1') {
            return;
        }
        setSubmitButtonLoading(button, true);

        var formData = new FormData(form);
        var tokenName = getCsrfTokenName(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '?option=com_ajax&module=iform&format=json', true);

        if (tokenName) {
            xhr.setRequestHeader('X-CSRF-Token', tokenName);
        }
        xhr.setRequestHeader('Cache-Control', 'no-cache');

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== XMLHttpRequest.DONE) {
                return;
            }

            setSubmitButtonLoading(button, false);

            if (xhr.status !== 200) {
                console.error('Checkout request failed with status:', xhr.status);
                return;
            }

            var data;
            try {
                data = JSON.parse(xhr.responseText);
            } catch (e) {
                console.error('Invalid JSON response', e);
                return;
            }

            checkoutUpdate(form, data);

            var errors = data && data.data && Array.isArray(data.data.errors) ? data.data.errors : [];
            if (errors.length !== 0) {
                return;
            }

            try {
                if (typeof gtag === 'function') {
                    gtag('event', 'purchase', {
                        transaction_id: Date.now().toString(16),
                        value: formData.get('total'),
                        shipping: 0,
                        currency: 'BYN',
                        items: typeof dataLayerItems !== 'undefined' ? dataLayerItems : []
                    });
                }
            } catch (e) {
                console.log('Error goal[ORDER]');
            }

            form.reset();
            form.classList.add('d-none');
            clearCart();

            var thankYou = document.querySelector(SELECTORS.thankYou);
            if (thankYou) {
                thankYou.classList.add('active');
            }
        };

        xhr.send(formData);
    }

    function bindCheckoutForm(form) {
        if (!form || form.dataset.checkoutBound === '1') {
            return;
        }

        form.dataset.checkoutBound = '1';

        form.addEventListener('submit', function (event) {
            submitCheckout(form, event);
        });
    }

    function init() {
        bindShipping();

        waitFor(
            function () {
                return document.querySelector(SELECTORS.form) &&
                    window.iTheme &&
                    typeof window.iTheme.Validate === 'function';
            },
            function () {
                var form = document.querySelector(SELECTORS.form);
                bindCheckoutForm(form);
            },
            {
                timeout: 15000,
                interval: 100
            }
        );
    }

    onReady(function () {
        init();

        var observer = new MutationObserver(function () {
            bindShipping();

            var form = document.querySelector(SELECTORS.form);
            if (form && window.iTheme && typeof window.iTheme.Validate === 'function') {
                bindCheckoutForm(form);
            }
        });

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true
        });
    });
})();
