/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

(function (window, document) {
    'use strict';

    window.iTheme = window.iTheme || {};

    function setEcommerce(action, productId, quantity) {
        if (typeof dataLayerItems === 'undefined' || !Array.isArray(dataLayerItems)) {
            console.error('Ошибка dataLayerItems');
            return;
        }

        const numericProductId = Number(productId);

        if (Number.isNaN(numericProductId)) {
            console.error('Некорректный productId:', productId);
            return;
        }

        const product = dataLayerItems.find(function (item) {
            return item.item_id === numericProductId;
        });

        if (!product) {
            console.error('Товар не найден в dataLayerItems:', productId);
            return;
        }

        if (typeof gtag !== 'function') {
            console.error('Функция gtag недоступна');
            return;
        }

        if (action === 'purchase') {
            gtag('event', action, {
                transaction_id: Date.now().toString(16),
                currency: 'BYN',
                value: product.price * quantity,
                shipping: 0,
                items: [{
                    ...product,
                    quantity: quantity
                }]
            });
        }

        gtag('event', action, {
            currency: 'BYN',
            value: product.price * quantity,
            items: [{
                ...product,
                quantity: quantity
            }]
        });
    }

    function setGoal(ymGoal = '', gtagGoal = '') {
        if (ymGoal !== '' && typeof ym === 'function' && typeof window.iTheme.metricaId !== 'undefined') {
            ym(window.iTheme.metricaId, 'reachGoal', ymGoal);
        }

        if (gtagGoal !== '' && typeof gtag === 'function') {
            gtag('event', gtagGoal);
        }
    }

    function prepareElements(selector, ymGoal = '', gtagGoal = '') {
        const elements = document.querySelectorAll(selector);

        elements.forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();

                const href = element.getAttribute('href');

                if (!href) {
                    console.warn('Нет атрибута href.');
                    return;
                }

                setGoal(ymGoal, gtagGoal);

                setTimeout(function () {
                    const target = element.getAttribute('target');

                    if (target === '_blank') {
                        window.open(href, '_blank');
                    } else {
                        window.location.href = href;
                    }
                }, 300);
            });
        });
    }

    window.iTheme.setEcommerce = setEcommerce;
    window.iTheme.setGoal = setGoal;

    prepareElements("a[href^='tel']", 'PHONE', 'PHONE_CLICK');
    prepareElements("a[href^='mailto']", 'EMAIL', 'EMAIL_CLICK');
    prepareElements("a[href^='viber']", 'VIBER', 'VIBER_CLICK');
    prepareElements("a[href^='https://t.me/']", 'TELEGRAM', 'TELEGRAM_CLICK');
    prepareElements("a[href^='https://vk.com/']", 'VK_CLICK', 'VK_CLICK');
    prepareElements("a[href^='https://ok.ru/']", 'OK_CLICK', 'OK_CLICK');
    prepareElements("a[href^='https://yandex.ru/maps/']", 'GET_MAP', 'GET_MAP');
})(window, document);