/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('buy1clickModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const rawData = button.getAttribute('data-1click');
        if (!rawData) return;

        let product;
        try {
            product = JSON.parse(rawData);
        } catch (error) {
            console.error('Ошибка парсинга data-1click:', error);
            return;
        }

        const form = modal.querySelector('form');
        const form_thank_you = modal.querySelector('.form_thank_you');
        const img = modal.querySelector('#trg-1click-img');
        const title = modal.querySelector('#trg-1click-title');
        const price = modal.querySelector('#trg-1click-price');
        const productsInput = modal.querySelector('input[name="products"]');
        const totalInput = modal.querySelector('input[name="total"]');
        const item_id = modal.querySelector('input[name="item_id"]');

        if (form) {
            form.classList.remove('d-none');
        }

        if (form_thank_you) {
            form_thank_you.classList.remove('active');
        }

        if (img && product.image) {
            img.src = '/' + String(product.image).replace(/^\/+/, '');
            img.alt = product.product_name || '';
        }

        if (title) {
            title.textContent = product.product_name || '';
        }

        if (price) {
            price.textContent = product.price ? product.price + ' BYN' : '';
        }

        if (productsInput) {
            productsInput.value = JSON.stringify([product]);
        }

        if (totalInput) {
            totalInput.value = product.price || '';
        }

        if (item_id) {
            item_id.value = product.product_id || '';
        }
    });
});