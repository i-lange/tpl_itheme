/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

Joomla = window.Joomla || {};

((Joomla, document) => {
    // Инициализация шаблона
    function initTemplate(event) {
        const modalEl = document.getElementById('ishopZonesModal');
        let returnFocusEl = null;

        if (!modalEl || modalEl.dataset.ithemeModalBound === '1') {
            return;
        }

        modalEl.dataset.ithemeModalBound = '1';

        modalEl.addEventListener('show.bs.modal', (event) => {
            returnFocusEl = event.relatedTarget || document.activeElement;
        });

        modalEl.addEventListener('hide.bs.modal', () => {
            if (modalEl.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });

        modalEl.addEventListener('hidden.bs.modal', () => {
            returnFocusEl?.focus?.();
        });
    }

    // Добавляем переход на главную страницу без ссылки
    function FrontpageRedirect() {
        this.handleClick = function(event) {
            const target = event.target.closest('[data-logo-click]');
            if (!target) return;

            event.preventDefault();
            const url = target.getAttribute('data-logo-click') || '/';

            if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button === 1) {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
        };

        document.addEventListener('click', this.handleClick);
    }

    function refreshLazyload(root = document) {
        if (typeof window.lozad !== 'function') {
            console.error('Lozad не загружен');
            return;
        }

        const elements = root === document ? 'img[data-srcset]' : root.querySelectorAll('img[data-srcset]');

        window.lozad(elements, {
            rootMargin: '300px 500px',
            threshold: 0.1,
            enableAutoReload: true
        }).observe();
    }

    const expandableMedia = window.matchMedia('(min-width: 768px)');
    const productButtonsMedia = window.matchMedia('(min-width: 992px)');
    let expandableResizeTimer = null;
    let productButtonsPlaceholder = null;
    let productButtonsScrollTicking = false;

    function getCssLengthInPixels(value, context = document.documentElement) {
        const length = String(value || '').trim();
        const size = Number.parseFloat(length);

        if (!Number.isFinite(size) || size <= 0) {
            return null;
        }

        if (length.endsWith('px') || !/[a-z%]+$/i.test(length)) {
            return size;
        }

        if (length.endsWith('rem')) {
            const rootFontSize = parseFloat(window.getComputedStyle(document.documentElement).fontSize);

            return size * (Number.isFinite(rootFontSize) ? rootFontSize : 16);
        }

        if (length.endsWith('em')) {
            const contextFontSize = parseFloat(window.getComputedStyle(context).fontSize);

            return size * (Number.isFinite(contextFontSize) ? contextFontSize : 16);
        }

        return null;
    }

    function getExpandableLimit(expandable) {
        const customLimit = getCssLengthInPixels(expandable.dataset.expandableMaxHeight, expandable);

        if (customLimit) {
            return customLimit;
        }

        const cssLimit = getCssLengthInPixels(
            window.getComputedStyle(expandable).getPropertyValue('--expandable-max-height'),
            expandable
        );

        return cssLimit || 256;
    }

    function getExpandableItems(root = document) {
        const items = [];

        if (root instanceof Element && root.matches('[data-expandable]')) {
            items.push(root);
        }

        if (root.querySelectorAll) {
            items.push(...root.querySelectorAll('[data-expandable]'));
        }

        return items;
    }

    function getExpandablePart(expandable, selector) {
        return expandable.querySelector(`:scope > ${selector}`);
    }

    function syncExpandable(expandable) {
        const content = getExpandablePart(expandable, '[data-expandable-content]');
        const toggle = getExpandablePart(expandable, '[data-expandable-toggle]');

        if (!content || !toggle) {
            return;
        }

        if (expandable.dataset.expandableMaxHeight) {
            expandable.style.setProperty('--expandable-max-height', expandable.dataset.expandableMaxHeight);
        }

        const isExpanded = expandable.dataset.expandableExpanded === 'true';

        expandable.classList.remove('is-collapsible', 'is-collapsed', 'is-expanded');
        toggle.hidden = true;
        toggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');

        if (!expandableMedia.matches) {
            return;
        }

        if (content.scrollHeight <= getExpandableLimit(expandable) + 1) {
            return;
        }

        expandable.classList.add('is-collapsible');

        if (isExpanded) {
            expandable.classList.add('is-expanded');
            return;
        }

        expandable.classList.add('is-collapsed');
        toggle.hidden = false;
    }

    function initExpandable(root = document) {
        getExpandableItems(root).forEach((expandable) => {
            const toggle = getExpandablePart(expandable, '[data-expandable-toggle]');

            if (toggle && toggle.dataset.expandableBound !== '1') {
                toggle.dataset.expandableBound = '1';
                toggle.addEventListener('click', () => {
                    expandable.dataset.expandableExpanded = 'true';
                    syncExpandable(expandable);
                });
            }

            syncExpandable(expandable);
        });
    }

    function getHeaderOffset() {
        return getCssLengthInPixels(
            window.getComputedStyle(document.documentElement).getPropertyValue('--header-offset'),
            document.documentElement
        ) || 0;
    }

    function shouldMoveProductButtons(buttons) {
        if (!productButtonsPlaceholder || !productButtonsPlaceholder.parentElement) {
            return false;
        }

        const sourceColumn = productButtonsPlaceholder.parentElement.closest('.product-full__summary')
            || productButtonsPlaceholder.parentElement.closest('.col-12');

        if (!sourceColumn) {
            return false;
        }

        const stickyTop = getHeaderOffset() + 16;
        const sourceBottom = sourceColumn.getBoundingClientRect().bottom;

        return window.scrollY > 0 && sourceBottom <= stickyTop;
    }

    function setProductButtonsSourceMode(buttons, offers) {
        if (productButtonsPlaceholder?.parentNode && buttons.previousSibling !== productButtonsPlaceholder) {
            productButtonsPlaceholder.after(buttons);
        }

        offers?.classList.remove('product-full__offers--wide');
        document.documentElement.classList.remove('is-product-buttons-sidebar-active');
    }

    function setProductButtonsSidebarMode(buttons, sidebar, offers) {
        if (buttons.parentElement !== sidebar) {
            sidebar.append(buttons);
        }

        offers?.classList.add('product-full__offers--wide');
        document.documentElement.classList.add('is-product-buttons-sidebar-active');
    }

    function syncProductButtonsPlacement(forceSource = false) {
        const buttons = document.getElementById('product-buttons');
        const sidebar = document.querySelector('[data-product-buttons-sidebar]');

        if (!buttons || !sidebar) {
            document.documentElement.classList.remove('is-product-buttons-sidebar-active');
            return;
        }

        if (!productButtonsPlaceholder || !productButtonsPlaceholder.isConnected) {
            productButtonsPlaceholder = document.createComment('product-buttons-placeholder');
            buttons.before(productButtonsPlaceholder);
        }

        const offers = document.getElementById('product-offers');

        if (!productButtonsMedia.matches || forceSource) {
            setProductButtonsSourceMode(buttons, offers);
            return;
        }

        if (shouldMoveProductButtons(buttons)) {
            setProductButtonsSidebarMode(buttons, sidebar, offers);
            return;
        }

        setProductButtonsSourceMode(buttons, offers);
    }

    function requestProductButtonsPlacementSync() {
        if (productButtonsScrollTicking) {
            return;
        }

        productButtonsScrollTicking = true;

        window.requestAnimationFrame(() => {
            productButtonsScrollTicking = false;
            syncProductButtonsPlacement();
        });
    }

    window.iTheme = window.iTheme || {};
    window.iTheme.refreshLazyload = refreshLazyload;

    document.addEventListener('DOMContentLoaded', event => {
        // Инициализация
        initTemplate(event);

        // Переход на главную
        const lagoHandler = new FrontpageRedirect();


        // Ленивая загрузка стилей css
        document.head.querySelectorAll('link[rel="lazy-stylesheet"]').forEach($link => {
            $link.rel = 'stylesheet';
        });


        // Ленивая загрузка изображений и фреймов
        refreshLazyload(document);

        initExpandable(document);
        syncProductButtonsPlacement();

    });

    function initFinderClearButton() {
        document.querySelectorAll('.js-finder-searchform').forEach((form) => {
            const input = form.querySelector('.js-finder-search-query');
            const clearBtn = form.querySelector('.btn-close');

            if (!clearBtn || !input) {
                return;
            }

            const toggleClearButton = () => {
                const hasValue = input.value.trim().length > 0;

                clearBtn.hidden = !hasValue;
                clearBtn.classList.toggle('d-none', !hasValue);
            };

            clearBtn.addEventListener('click', function () {
                input.value = '';
                input.focus();
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });

            input.addEventListener('input', toggleClearButton);
            input.addEventListener('change', toggleClearButton);
            toggleClearButton();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initFinderClearButton();
    });

    // Инициализируется при обновлении части страницы
    document.addEventListener('joomla:updated', event => {
        initTemplate(event);
        initExpandable(event.target instanceof Element ? event.target : document);
        syncProductButtonsPlacement(true);
        syncProductButtonsPlacement();
    });
    document.addEventListener('com_ishop:products-loaded', event => {
        refreshLazyload(event.detail && event.detail.container ? event.detail.container : document);
    });

    expandableMedia.addEventListener('change', () => {
        initExpandable(document);
    });

    productButtonsMedia.addEventListener('change', () => {
        syncProductButtonsPlacement();
    });

    window.addEventListener('load', () => {
        initExpandable(document);
        syncProductButtonsPlacement();
    });

    window.addEventListener('scroll', () => {
        requestProductButtonsPlacementSync();
    }, { passive: true });

    window.addEventListener('resize', () => {
        window.clearTimeout(expandableResizeTimer);
        expandableResizeTimer = window.setTimeout(() => {
            initExpandable(document);
            syncProductButtonsPlacement();
        }, 120);
    });

})(Joomla, document);
