(function () {
    'use strict';

    var SELECTORS = {
        shippingRadios: 'input[name="shipping"]',
        shippingParams: '.checkout-shipping-params',
        cart: '[data-ishop-cart]',
        form: '#checkout-submit',
        thankYou: '.checkout_thank_you'
    };

    var CART_CLEAR_URL = '/index.php?option=com_ishop&task=cart.remove&format=json';

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

    function getJoomlaCsrfToken(fallbackToken) {
        if (!window.Joomla || typeof window.Joomla.getOptions !== 'function') {
            return fallbackToken || '';
        }

        return window.Joomla.getOptions('csrf.token', '') || fallbackToken || '';
    }

    function buildCartClearData(fallbackToken) {
        var formData = new FormData();
        var csrfToken = getJoomlaCsrfToken(fallbackToken);

        if (csrfToken) {
            formData.append(csrfToken, '1');
        }

        return formData;
    }

    function parseCartResponse(responseText) {
        var response = JSON.parse(responseText || '{}');

        if (!response || response.success !== true) {
            throw new Error(response && response.message ? response.message : 'Cart clear request failed');
        }

        return response;
    }

    function updateCartModules(count) {
        var normalizedCount = Math.max(0, parseInt(count, 10) || 0);

        document.querySelectorAll(SELECTORS.cart).forEach(function (cart) {
            var counter = cart.querySelector('[data-ishop-cart-count], small');
            var countText = cart.querySelector('[data-ishop-cart-count-text]');

            if (counter) {
                counter.textContent = String(normalizedCount);
            }

            if (countText) {
                countText.textContent = normalizedCount === 0
                    ? cart.dataset.ishopCartEmptyText || ''
                    : String(normalizedCount);
            }

            if (cart.hasAttribute('aria-label')) {
                var label = cart.getAttribute('aria-label') || '';
                var prefix = label.indexOf(':') !== -1 ? label.split(':')[0] : label;

                cart.setAttribute('aria-label', prefix ? prefix + ': ' + normalizedCount : String(normalizedCount));
            }

            cart.dataset.ishopCartEmpty = normalizedCount === 0 ? '1' : '0';
        });
    }

    function dispatchCartUpdated(data) {
        document.dispatchEvent(new CustomEvent('com_ishop:cart-updated', {
            bubbles: true,
            detail: {
                data: data || { count: 0 },
                source: 'tpl.checkout'
            }
        }));
    }

    function handleCartClearSuccess(response) {
        var data = response && response.data ? response.data : {};
        var count = data.count !== undefined && data.count !== null ? data.count : 0;

        updateCartModules(count);
        dispatchCartUpdated(data);
    }

    function clearCartWithJoomlaRequest(tokenName) {
        return new Promise(function (resolve, reject) {
            window.Joomla.request({
                url: CART_CLEAR_URL,
                method: 'POST',
                data: buildCartClearData(tokenName),
                headers: {
                    'Cache-Control': 'no-cache'
                },
                onSuccess: function (responseText) {
                    try {
                        resolve(parseCartResponse(responseText));
                    } catch (e) {
                        reject(e);
                    }
                },
                onError: function (xhr) {
                    reject(new Error('Cart clear request failed with status: ' + (xhr.status || 0)));
                }
            });
        });
    }

    function clearCartWithXhr(tokenName) {
        return new Promise(function (resolve, reject) {
            var request = new XMLHttpRequest();

            request.open('POST', CART_CLEAR_URL, true);
            request.setRequestHeader('Cache-Control', 'no-cache');
            request.onreadystatechange = function () {
                if (request.readyState !== XMLHttpRequest.DONE) {
                    return;
                }

                if (request.status !== 200) {
                    reject(new Error('Cart clear request failed with status: ' + request.status));
                    return;
                }

                try {
                    resolve(parseCartResponse(request.responseText));
                } catch (e) {
                    reject(e);
                }
            };

            request.send(buildCartClearData(tokenName));
        });
    }

    function clearCart(tokenName) {
        var request = window.Joomla && typeof window.Joomla.request === 'function'
            ? clearCartWithJoomlaRequest(tokenName)
            : clearCartWithXhr(tokenName);

        request
            .then(handleCartClearSuccess)
            .catch(function (error) {
                console.error('Cart clear error:', error);
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

            document.dispatchEvent(new CustomEvent('isiteanalytics:ecommerce', {
                bubbles: true,
                detail: {
                    event: 'purchase',
                    transaction_id: Date.now().toString(16),
                    value: form.dataset.isiteanalyticsValue || formData.get('total'),
                    currency: form.dataset.isiteanalyticsCurrency || 'BYN',
                    items: parseAnalyticsItems(form),
                    source: 'tpl.checkout'
                }
            }));

            form.reset();
            form.classList.add('d-none');
            clearCart(tokenName);

            var thankYou = document.querySelector(SELECTORS.thankYou);
            if (thankYou) {
                thankYou.classList.add('active');
            }
        };

        xhr.send(formData);
    }

    function parseAnalyticsItems(form) {
        if (!form || !form.dataset.isiteanalyticsItems) {
            return [];
        }

        try {
            const items = JSON.parse(form.dataset.isiteanalyticsItems);

            return Array.isArray(items) ? items : [];
        } catch (e) {
            console.error('Invalid checkout analytics items', e);
            return [];
        }
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
