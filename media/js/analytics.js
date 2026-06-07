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
    window.iTheme.ecommerceItems = window.iTheme.ecommerceItems || [];

    function getLegacyEcommerceItems() {
        if (typeof dataLayerItems !== 'undefined' && Array.isArray(dataLayerItems)) {
            return dataLayerItems;
        }

        if (Array.isArray(window.dataLayerItems)) {
            return window.dataLayerItems;
        }

        return [];
    }

    function registerEcommerceItems(items) {
        if (!Array.isArray(items) || items.length === 0) {
            return;
        }

        items.forEach(function (item) {
            if (!item || typeof item.item_id === 'undefined') {
                return;
            }

            const numericItemId = Number(item.item_id);

            if (Number.isNaN(numericItemId)) {
                return;
            }

            const normalizedItem = {
                ...item,
                item_id: numericItemId
            };
            const currentIndex = window.iTheme.ecommerceItems.findIndex(function (storedItem) {
                return Number(storedItem.item_id) === numericItemId;
            });

            if (currentIndex >= 0) {
                window.iTheme.ecommerceItems[currentIndex] = normalizedItem;
            } else {
                window.iTheme.ecommerceItems.push(normalizedItem);
            }
        });

        window.dataLayerItems = window.iTheme.ecommerceItems;
    }

    function findEcommerceItem(productId) {
        const numericProductId = Number(productId);

        if (Number.isNaN(numericProductId)) {
            return null;
        }

        const items = window.iTheme.ecommerceItems.concat(getLegacyEcommerceItems());

        return items.find(function (item) {
            return Number(item.item_id) === numericProductId;
        }) || null;
    }

    function trackViewItemList(payload) {
        payload = payload || {};
        const items = Array.isArray(payload.items) ? payload.items : [];

        registerEcommerceItems(items);

        if (items.length === 0 || typeof gtag !== 'function') {
            return;
        }

        gtag('event', 'view_item_list', {
            currency: payload.currency || 'BYN',
            item_list_id: payload.item_list_id || '',
            item_list_name: payload.item_list_name || '',
            items: items
        });
    }

    function setEcommerce(action, productId, quantity) {
        const numericProductId = Number(productId);

        if (Number.isNaN(numericProductId)) {
            console.error('Некорректный productId:', productId);
            return;
        }

        const product = findEcommerceItem(numericProductId);

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
    window.iTheme.registerEcommerceItems = registerEcommerceItems;
    window.iTheme.trackViewItemList = trackViewItemList;

    registerEcommerceItems(window.iThemePendingEcommerceItems || []);
    registerEcommerceItems(getLegacyEcommerceItems());

    prepareElements("a[href^='tel']", 'PHONE', 'PHONE_CLICK');
    prepareElements("a[href^='mailto']", 'EMAIL', 'EMAIL_CLICK');
    prepareElements("a[href^='viber']", 'VIBER', 'VIBER_CLICK');
    prepareElements("a[href^='https://t.me/']", 'TELEGRAM', 'TELEGRAM_CLICK');
    prepareElements("a[href^='https://vk.com/']", 'VK_CLICK', 'VK_CLICK');
    prepareElements("a[href^='https://ok.ru/']", 'OK_CLICK', 'OK_CLICK');
    prepareElements("a[href^='https://yandex.ru/maps/']", 'GET_MAP', 'GET_MAP');
})(window, document);
