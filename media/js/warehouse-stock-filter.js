/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

((document) => {
    const ROOT_SELECTOR = '[data-warehouse-stock-filter]';
    const BUTTON_SELECTOR = '[data-instock-category]';
    const PRODUCT_SELECTOR = '[data-instock-product-category]';

    function getRoots(root = document) {
        const roots = [];

        if (root instanceof Element && root.matches(ROOT_SELECTOR)) {
            roots.push(root);
        }

        if (root.querySelectorAll) {
            roots.push(...root.querySelectorAll(ROOT_SELECTOR));
        }

        return roots;
    }

    function setActiveButton(root, activeButton) {
        root.querySelectorAll(BUTTON_SELECTOR).forEach((button) => {
            const isActive = button === activeButton;

            button.classList.toggle('active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    function setVisibleProducts(root, categoryAlias) {
        root.querySelectorAll(PRODUCT_SELECTOR).forEach((product) => {
            product.hidden = categoryAlias !== '' && product.dataset.instockProductCategory !== categoryAlias;
        });
    }

    function syncRoot(root) {
        const activeButton = root.querySelector(`${BUTTON_SELECTOR}.active`) || root.querySelector(BUTTON_SELECTOR);

        if (activeButton) {
            setActiveButton(root, activeButton);
            setVisibleProducts(root, activeButton.dataset.instockCategory || '');
        }
    }

    function bindRoot(root) {
        if (root.dataset.warehouseStockFilterBound !== '1') {
            root.dataset.warehouseStockFilterBound = '1';

            root.addEventListener('click', (event) => {
                const target = event.target instanceof Element ? event.target : null;
                const button = target ? target.closest(BUTTON_SELECTOR) : null;

                if (!button || !root.contains(button)) {
                    return;
                }

                setActiveButton(root, button);
                setVisibleProducts(root, button.dataset.instockCategory || '');
            });
        } else {
            syncRoot(root);
            return;
        }

        syncRoot(root);
    }

    function init(root = document) {
        getRoots(root).forEach(bindRoot);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => init(), { once: true });
    } else {
        init();
    }

    document.addEventListener('joomla:updated', (event) => {
        init(event.target instanceof Element ? event.target : document);
    });
})(document);
