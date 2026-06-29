(function (window, document) {
    'use strict';

    const DEFAULT_SELECTOR = '[data-drag-scroller]';
    const IGNORE_SELECTOR = 'button, input, textarea, select, label, [contenteditable="true"], [data-drag-scroller-ignore]';
    const DEFAULT_THRESHOLD = 6;
    const DEFAULT_DIRECTION_RATIO = 1.15;

    const DragScroller = {
        states: new Map(),
        activeSlider: null,
        selector: DEFAULT_SELECTOR,
        scrollSpeed: 1,
        threshold: DEFAULT_THRESHOLD,
        directionRatio: DEFAULT_DIRECTION_RATIO,
        boundHandlers: null,

        init(options = {}) {
            if (typeof options === 'string') {
                options = { selector: options };
            }

            this.destroy();

            this.selector = options.selector || DEFAULT_SELECTOR;
            this.scrollSpeed = Number.parseFloat(options.scrollSpeed) || 1;
            this.threshold = Number.parseFloat(options.threshold) || DEFAULT_THRESHOLD;
            this.directionRatio = Number.parseFloat(options.directionRatio) || DEFAULT_DIRECTION_RATIO;

            this.boundHandlers = {
                pointerdown: this.handlePointerDown.bind(this),
                pointermove: this.handlePointerMove.bind(this),
                pointerup: this.handlePointerUp.bind(this),
                pointercancel: this.handlePointerCancel.bind(this),
                click: this.handleClick.bind(this),
                dragstart: this.preventNativeDrag.bind(this),
                joomlaUpdated: this.handleJoomlaUpdated.bind(this),
                productsLoaded: this.handleProductsLoaded.bind(this)
            };

            this.refresh(document);

            document.addEventListener('pointerdown', this.boundHandlers.pointerdown, { passive: true });
            document.addEventListener('pointermove', this.boundHandlers.pointermove, { passive: false });
            document.addEventListener('pointerup', this.boundHandlers.pointerup);
            document.addEventListener('pointercancel', this.boundHandlers.pointercancel);
            document.addEventListener('click', this.boundHandlers.click, true);
            document.addEventListener('dragstart', this.boundHandlers.dragstart);
            document.addEventListener('joomla:updated', this.boundHandlers.joomlaUpdated);
            document.addEventListener('com_ishop:products-loaded', this.boundHandlers.productsLoaded);

            window.iTheme = window.iTheme || {};
            window.iTheme.dragScroller = this;

            return this;
        },

        getInitialState() {
            return {
                isPending: false,
                isDragging: false,
                pointerId: null,
                startX: 0,
                startY: 0,
                scrollLeft: 0,
                speed: this.scrollSpeed,
                suppressClick: false,
                suppressClickTimer: null
            };
        },

        refresh(context = document) {
            if (typeof context === 'string') {
                document.querySelectorAll(context).forEach((slider) => this.registerSlider(slider));
                return this;
            }

            if (context instanceof Element && context.matches(this.selector)) {
                this.registerSlider(context);
            }

            if (context.querySelectorAll) {
                context.querySelectorAll(this.selector).forEach((slider) => this.registerSlider(slider));
            }

            return this;
        },

        registerSlider(slider) {
            if (!this.states.has(slider)) {
                this.states.set(slider, this.getInitialState());
            }
        },

        handlePointerDown(event) {
            if (event.button !== 0 || event.isPrimary === false) {
                return;
            }

            if (event.target.closest(IGNORE_SELECTOR)) {
                return;
            }

            const slider = event.target.closest(this.selector);

            if (!slider || slider.scrollWidth <= slider.clientWidth) {
                return;
            }

            this.registerSlider(slider);
            this.startPending(slider, event);
        },

        handlePointerMove(event) {
            if (!this.activeSlider) {
                return;
            }

            const state = this.states.get(this.activeSlider);

            if (!state || state.pointerId !== event.pointerId) {
                return;
            }

            const deltaX = event.clientX - state.startX;
            const deltaY = event.clientY - state.startY;
            const absX = Math.abs(deltaX);
            const absY = Math.abs(deltaY);

            if (state.isPending) {
                if (absX < this.threshold && absY < this.threshold) {
                    return;
                }

                if (absY >= this.threshold && absY > absX) {
                    this.stopTracking(false);
                    return;
                }

                if (absX < this.threshold || absX < absY * this.directionRatio) {
                    return;
                }

                this.startDragging(this.activeSlider, event);
            }

            if (!state.isDragging) {
                return;
            }

            event.preventDefault();
            this.activeSlider.scrollLeft = state.scrollLeft - deltaX * state.speed;
        },

        handlePointerUp(event) {
            if (!this.activeSlider) {
                return;
            }

            const state = this.states.get(this.activeSlider);

            if (state && state.pointerId !== null && event.pointerId !== state.pointerId) {
                return;
            }

            this.stopTracking(Boolean(state && state.isDragging));
        },

        handlePointerCancel(event) {
            if (!this.activeSlider) {
                return;
            }

            const state = this.states.get(this.activeSlider);

            if (state && state.pointerId !== null && event.pointerId !== state.pointerId) {
                return;
            }

            this.stopTracking(false);
        },

        startPending(slider, event) {
            const state = this.states.get(slider);

            if (!state) {
                return;
            }

            if (this.activeSlider && this.activeSlider !== slider) {
                this.stopTracking(false);
            }

            window.clearTimeout(state.suppressClickTimer);
            state.isPending = true;
            state.isDragging = false;
            state.pointerId = event.pointerId;
            state.startX = event.clientX;
            state.startY = event.clientY;
            state.scrollLeft = slider.scrollLeft;
            state.speed = Number.parseFloat(slider.dataset.dragScrollSpeed) || this.scrollSpeed;

            this.activeSlider = slider;
        },

        startDragging(slider, event) {
            const state = this.states.get(slider);

            if (!state) {
                return;
            }

            state.isPending = false;
            state.isDragging = true;
            slider.classList.add('is-dragging');
            slider.style.userSelect = 'none';

            if (typeof slider.setPointerCapture === 'function') {
                try {
                    slider.setPointerCapture(event.pointerId);
                } catch (error) {
                    // Pointer capture may fail if the pointer is already cancelled by the browser.
                }
            }
        },

        stopTracking(suppressClick) {
            if (!this.activeSlider) {
                return;
            }

            const slider = this.activeSlider;
            const state = this.states.get(slider);

            slider.classList.remove('is-dragging');
            slider.style.removeProperty('user-select');

            if (state) {
                state.isPending = false;
                state.isDragging = false;
                state.pointerId = null;

                if (suppressClick) {
                    state.suppressClick = true;
                    window.clearTimeout(state.suppressClickTimer);
                    state.suppressClickTimer = window.setTimeout(() => {
                        state.suppressClick = false;
                    }, 400);
                }
            }

            this.activeSlider = null;
        },

        handleClick(event) {
            const slider = event.target.closest(this.selector);

            if (!slider) {
                return;
            }

            const state = this.states.get(slider);

            if (!state || !state.suppressClick) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            state.suppressClick = false;
            window.clearTimeout(state.suppressClickTimer);
        },

        preventNativeDrag(event) {
            if (event.target.closest(this.selector)) {
                event.preventDefault();
            }
        },

        handleJoomlaUpdated(event) {
            this.refresh(event.target instanceof Element ? event.target : document);
        },

        handleProductsLoaded(event) {
            const container = event.detail && event.detail.container instanceof Element
                ? event.detail.container
                : document;

            this.refresh(container);
        },

        destroy() {
            if (this.boundHandlers) {
                document.removeEventListener('pointerdown', this.boundHandlers.pointerdown);
                document.removeEventListener('pointermove', this.boundHandlers.pointermove);
                document.removeEventListener('pointerup', this.boundHandlers.pointerup);
                document.removeEventListener('pointercancel', this.boundHandlers.pointercancel);
                document.removeEventListener('click', this.boundHandlers.click, true);
                document.removeEventListener('dragstart', this.boundHandlers.dragstart);
                document.removeEventListener('joomla:updated', this.boundHandlers.joomlaUpdated);
                document.removeEventListener('com_ishop:products-loaded', this.boundHandlers.productsLoaded);
            }

            this.states.forEach((state) => {
                window.clearTimeout(state.suppressClickTimer);
            });

            if (this.activeSlider) {
                this.activeSlider.classList.remove('is-dragging');
                this.activeSlider.style.removeProperty('user-select');
            }

            this.states.clear();
            this.activeSlider = null;
            this.boundHandlers = null;

            return this;
        }
    };

    const init = () => {
        DragScroller.init();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
}(window, document));
