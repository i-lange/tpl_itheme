/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

(function (window, document) {
    'use strict';

    const PANEL_SELECTOR = '[data-category-sticky-toolbar]';
    const instances = new WeakMap();

    class CategoryStickyToolbar {
        constructor(panel) {
            this.panel = panel;
            this.root = panel.closest('[data-category-sticky-toolbar-root]') || panel.parentElement;
            this.spacer = document.createElement('div');
            this.header = document.querySelector('#header');
            this.isFixed = false;
            this.ticking = false;
            this.panelHeight = 0;
            this.marginTop = 0;
            this.marginBottom = 0;
            this.outerHeight = 0;

            this.requestUpdate = this.requestUpdate.bind(this);
            this.handleResize = this.handleResize.bind(this);
            this.handleJoomlaUpdated = this.handleJoomlaUpdated.bind(this);
            this.handleProductsLoaded = this.handleProductsLoaded.bind(this);
        }

        init() {
            if (!this.root || !this.panel) {
                return;
            }

            this.spacer.className = 'category-toolbar-spacer';
            this.spacer.setAttribute('aria-hidden', 'true');
            this.panel.before(this.spacer);

            window.addEventListener('scroll', this.requestUpdate, { passive: true });
            window.addEventListener('resize', this.handleResize);
            document.addEventListener('joomla:updated', this.handleJoomlaUpdated);
            document.addEventListener('com_ishop:products-loaded', this.handleProductsLoaded);
            document.addEventListener('itheme:sticky-offset-updated', this.requestUpdate);

            if (typeof ResizeObserver === 'function') {
                this.resizeObserver = new ResizeObserver(this.handleResize);
                this.resizeObserver.observe(this.panel);
            }

            this.measure();
            this.requestUpdate();
        }

        getStickyOffset() {
            const rootStyle = window.getComputedStyle(document.documentElement);
            const inlineStickyOffset = document.documentElement.style.getPropertyValue('--header-sticky-offset');
            const stickyOffset = Number.parseFloat(rootStyle.getPropertyValue('--header-sticky-offset'));

            if (inlineStickyOffset && Number.isFinite(stickyOffset)) {
                return stickyOffset;
            }

            const inlineHeaderOffset = document.documentElement.style.getPropertyValue('--header-offset');
            const headerOffset = Number.parseFloat(rootStyle.getPropertyValue('--header-offset'));

            if (inlineHeaderOffset && Number.isFinite(headerOffset)) {
                return headerOffset;
            }

            if (!this.header) {
                return 0;
            }

            return Math.max(this.header.getBoundingClientRect().bottom, 0);
        }

        measure() {
            const wasFixed = this.isFixed;

            if (wasFixed) {
                this.applyStatic();
            }

            const panelStyle = window.getComputedStyle(this.panel);

            this.marginTop = Number.parseFloat(panelStyle.marginTop) || 0;
            this.marginBottom = Number.parseFloat(panelStyle.marginBottom) || 0;
            this.panelHeight = this.panel.getBoundingClientRect().height;
            this.outerHeight = this.marginTop + this.panelHeight + this.marginBottom;

            if (wasFixed) {
                this.isFixed = false;
            }
        }

        handleResize() {
            this.measure();
            this.requestUpdate();
        }

        handleJoomlaUpdated(event) {
            if (
                !event.target
                || event.target === document
                || (event.target instanceof Element && (this.root.contains(event.target) || event.target.contains(this.root)))
            ) {
                this.handleResize();
            }
        }

        handleProductsLoaded(event) {
            const container = event.detail && event.detail.container;

            if (
                !container
                || !(container instanceof Element)
                || this.root.contains(container)
                || container.contains(this.root)
            ) {
                this.handleResize();
            }
        }

        requestUpdate() {
            if (this.ticking) {
                return;
            }

            this.ticking = true;

            window.requestAnimationFrame(() => {
                this.ticking = false;
                this.update();
            });
        }

        update() {
            if (!this.panel.isConnected || !this.root.isConnected) {
                this.destroy();
                return;
            }

            const stickyOffset = this.getStickyOffset();
            const anchorTop = this.spacer.getBoundingClientRect().top + this.marginTop;
            const rootBottom = this.root.getBoundingClientRect().bottom;
            const hasVerticalRoom = rootBottom > stickyOffset + this.panelHeight + this.marginBottom;
            const shouldFix = anchorTop <= stickyOffset && hasVerticalRoom;

            if (shouldFix) {
                this.applyFixed(stickyOffset);
                return;
            }

            this.applyStatic();
        }

        applyFixed(stickyOffset) {
            const rootRect = this.root.getBoundingClientRect();
            const rootStyle = window.getComputedStyle(this.root);
            const paddingLeft = Number.parseFloat(rootStyle.paddingLeft) || 0;
            const paddingRight = Number.parseFloat(rootStyle.paddingRight) || 0;
            const left = rootRect.left + paddingLeft;
            const width = Math.max(rootRect.width - paddingLeft - paddingRight, 0);

            if (!this.isFixed) {
                this.panel.classList.add('is-category-toolbar-fixed');
                this.isFixed = true;
            }

            this.spacer.style.height = `${this.outerHeight}px`;
            this.panel.style.top = `${stickyOffset}px`;
            this.panel.style.left = `${left}px`;
            this.panel.style.width = `${width}px`;
        }

        applyStatic() {
            if (this.isFixed) {
                this.panel.classList.remove('is-category-toolbar-fixed');
                this.isFixed = false;
            }

            this.spacer.style.removeProperty('height');
            this.panel.style.removeProperty('top');
            this.panel.style.removeProperty('left');
            this.panel.style.removeProperty('width');
        }

        destroy() {
            window.removeEventListener('scroll', this.requestUpdate);
            window.removeEventListener('resize', this.handleResize);
            document.removeEventListener('joomla:updated', this.handleJoomlaUpdated);
            document.removeEventListener('com_ishop:products-loaded', this.handleProductsLoaded);
            document.removeEventListener('itheme:sticky-offset-updated', this.requestUpdate);

            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
            }

            this.applyStatic();
            this.spacer.remove();
        }
    }

    function initCategoryStickyToolbars(context = document) {
        const panels = [];

        if (context instanceof Element && context.matches(PANEL_SELECTOR)) {
            panels.push(context);
        }

        if (context.querySelectorAll) {
            panels.push(...context.querySelectorAll(PANEL_SELECTOR));
        }

        panels.forEach((panel) => {
            if (instances.has(panel)) {
                instances.get(panel).handleResize();
                return;
            }

            const instance = new CategoryStickyToolbar(panel);
            instances.set(panel, instance);
            instance.init();
        });
    }

    const init = () => initCategoryStickyToolbars();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

    document.addEventListener('joomla:updated', (event) => {
        initCategoryStickyToolbars(event.target instanceof Element ? event.target : document);
    });
}(window, document));
