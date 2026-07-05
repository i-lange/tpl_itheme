/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

(function (window, document) {
    'use strict';

    const ROOT_SELECTOR = '[data-compare-sticky]';
    const PANEL_SELECTOR = '[data-compare-sticky-panel]';
    const TRACK_SELECTOR = '[data-compare-sticky-track]';
    const instances = new WeakMap();

    class CompareStickyPanel {
        constructor(scroller) {
            this.scroller = scroller;
            this.inner = scroller.querySelector('.module-compare-scroll-inner');
            this.panel = scroller.querySelector(PANEL_SELECTOR);
            this.track = this.panel ? this.panel.querySelector(TRACK_SELECTOR) || this.panel : null;
            this.spacer = document.createElement('div');
            this.header = document.querySelector('#header');
            this.isFixed = false;
            this.ticking = false;
            this.panelHeight = 0;
            this.trackWidth = 0;
            this.marginTop = 0;
            this.marginBottom = 0;
            this.outerHeight = 0;

            this.requestUpdate = this.requestUpdate.bind(this);
            this.handleResize = this.handleResize.bind(this);
            this.handleJoomlaUpdated = this.handleJoomlaUpdated.bind(this);
        }

        init() {
            if (!this.inner || !this.panel || !this.track) {
                return;
            }

            this.spacer.className = 'module-compare-products-spacer';
            this.spacer.setAttribute('aria-hidden', 'true');
            this.panel.before(this.spacer);

            window.addEventListener('scroll', this.requestUpdate, { passive: true });
            window.addEventListener('resize', this.handleResize);
            this.scroller.addEventListener('scroll', this.requestUpdate, { passive: true });
            document.addEventListener('joomla:updated', this.handleJoomlaUpdated);
            document.addEventListener('itheme:sticky-offset-updated', this.requestUpdate);

            if (typeof ResizeObserver === 'function') {
                this.resizeObserver = new ResizeObserver(this.handleResize);
                this.resizeObserver.observe(this.panel);
                this.resizeObserver.observe(this.track);
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
            this.trackWidth = this.track.getBoundingClientRect().width;
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
                !event.target ||
                event.target === document ||
                (event.target instanceof Element && event.target.contains(this.scroller))
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
            if (!this.scroller.isConnected || !this.panel.isConnected) {
                this.destroy();
                return;
            }

            const stickyOffset = this.getStickyOffset();
            const anchorTop = this.spacer.getBoundingClientRect().top + this.marginTop;
            const scrollerBottom = this.scroller.getBoundingClientRect().bottom;
            const hasVerticalRoom = scrollerBottom > stickyOffset + this.panelHeight;
            const shouldFix = anchorTop <= stickyOffset && hasVerticalRoom;

            if (shouldFix) {
                this.applyFixed(stickyOffset);
                return;
            }

            this.applyStatic();
        }

        applyFixed(stickyOffset) {
            const scrollerRect = this.scroller.getBoundingClientRect();
            const visibleWidth = this.scroller.clientWidth || scrollerRect.width;

            if (!this.isFixed) {
                this.scroller.classList.add('is-compare-sticky-active');
                this.panel.classList.add('is-compare-sticky-fixed');
                this.isFixed = true;
            }

            this.spacer.style.width = `${this.trackWidth}px`;
            this.spacer.style.height = `${this.outerHeight}px`;
            this.panel.style.top = `${stickyOffset}px`;
            this.panel.style.left = `${scrollerRect.left}px`;
            this.panel.style.width = `${visibleWidth}px`;
            this.track.style.width = `${this.trackWidth}px`;
            this.track.style.transform = `translateX(-${this.scroller.scrollLeft}px)`;
        }

        applyStatic() {
            if (this.isFixed) {
                this.scroller.classList.remove('is-compare-sticky-active');
                this.panel.classList.remove('is-compare-sticky-fixed');
                this.isFixed = false;
            }

            this.spacer.style.removeProperty('width');
            this.spacer.style.removeProperty('height');
            this.panel.style.removeProperty('top');
            this.panel.style.removeProperty('left');
            this.panel.style.removeProperty('width');
            this.track.style.removeProperty('width');
            this.track.style.removeProperty('transform');
        }

        destroy() {
            window.removeEventListener('scroll', this.requestUpdate);
            window.removeEventListener('resize', this.handleResize);
            this.scroller.removeEventListener('scroll', this.requestUpdate);
            document.removeEventListener('joomla:updated', this.handleJoomlaUpdated);
            document.removeEventListener('itheme:sticky-offset-updated', this.requestUpdate);

            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
            }

            this.applyStatic();
            this.spacer.remove();
        }
    }

    function initCompareSticky(context = document) {
        const roots = [];

        if (context instanceof Element && context.matches(ROOT_SELECTOR)) {
            roots.push(context);
        }

        if (context.querySelectorAll) {
            roots.push(...context.querySelectorAll(ROOT_SELECTOR));
        }

        roots.forEach((scroller) => {
            if (instances.has(scroller)) {
                instances.get(scroller).handleResize();
                return;
            }

            const instance = new CompareStickyPanel(scroller);
            instances.set(scroller, instance);
            instance.init();
        });
    }

    const init = () => initCompareSticky();

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

    document.addEventListener('joomla:updated', (event) => {
        initCompareSticky(event.target instanceof Element ? event.target : document);
    });
}(window, document));
