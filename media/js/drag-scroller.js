const DragScroller = {
    states: new Map(),
    activeSlider: null,
    scrollSpeed: 1,
    selector: '[data-drag-scroller]',
    boundHandlers: null,

    init(selector = '[data-drag-scroller]', scrollSpeed = 1) {
        this.destroy();

        this.selector = selector;
        this.scrollSpeed = scrollSpeed;

        this.boundHandlers = {
            pointerdown: this.handlePointerDown.bind(this),
            pointermove: this.handlePointerMove.bind(this),
            pointerup: this.handlePointerUp.bind(this),
            pointercancel: this.handlePointerUp.bind(this),
            dragstart: this.preventNativeDrag.bind(this)
        };

        this.registerSliders(selector);

        document.addEventListener('pointerdown', this.boundHandlers.pointerdown, { passive: true });
        document.addEventListener('pointermove', this.boundHandlers.pointermove, { passive: false });
        document.addEventListener('pointerup', this.boundHandlers.pointerup);
        document.addEventListener('pointercancel', this.boundHandlers.pointercancel);
        document.addEventListener('dragstart', this.boundHandlers.dragstart);

        return this;
    },

    registerSliders(selector = this.selector) {
        document.querySelectorAll(selector).forEach((slider) => {
            if (!this.states.has(slider)) {
                this.states.set(slider, {
                    isDown: false,
                    startX: 0,
                    scrollLeft: 0,
                    pointerId: null,
                    moved: false
                });
            }
        });
    },

    handlePointerDown(e) {
        if (e.button !== 0) return;

        const interactive = e.target.closest('a, button, input, textarea, select, label');
        if (interactive) return;

        const slider = e.target.closest(this.selector);
        if (!slider || !this.states.has(slider)) return;

        this.startDrag(slider, e);
    },

    handlePointerMove(e) {
        if (!this.activeSlider) return;

        const state = this.states.get(this.activeSlider);
        if (!state || !state.isDown || state.pointerId !== e.pointerId) return;

        e.preventDefault();
        this.processDrag(this.activeSlider, e);
    },

    handlePointerUp(e) {
        if (!this.activeSlider) return;

        const state = this.states.get(this.activeSlider);
        if (state && state.pointerId !== null && e.pointerId !== state.pointerId) return;

        this.stopDrag();
    },

    startDrag(slider, e) {
        const state = this.states.get(slider);
        if (!state) return;

        state.isDown = true;
        state.pointerId = e.pointerId;
        state.moved = false;
        state.startX = e.clientX - slider.getBoundingClientRect().left;
        state.scrollLeft = slider.scrollLeft;

        this.activeSlider = slider;

        slider.style.cursor = 'grabbing';
        slider.style.userSelect = 'none';
    },

    processDrag(slider, e) {
        const state = this.states.get(slider);
        if (!state || !state.isDown) return;

        const x = e.clientX - slider.getBoundingClientRect().left;
        const walk = (x - state.startX) * this.scrollSpeed;

        if (Math.abs(walk) > 3) {
            state.moved = true;
        }

        slider.scrollLeft = state.scrollLeft - walk;
    },

    stopDrag() {
        if (!this.activeSlider) return;

        const slider = this.activeSlider;
        const state = this.states.get(slider);

        if (state) {
            state.isDown = false;
            state.pointerId = null;
            state.moved = false;
        }

        slider.style.removeProperty('cursor');
        slider.style.removeProperty('user-select');

        this.activeSlider = null;
    },

    preventNativeDrag(e) {
        if (e.target.closest(this.selector)) {
            e.preventDefault();
        }
    },

    refresh(selector = this.selector) {
        this.registerSliders(selector);
        return this;
    },

    destroy() {
        if (this.boundHandlers) {
            document.removeEventListener('pointerdown', this.boundHandlers.pointerdown);
            document.removeEventListener('pointermove', this.boundHandlers.pointermove);
            document.removeEventListener('pointerup', this.boundHandlers.pointerup);
            document.removeEventListener('pointercancel', this.boundHandlers.pointercancel);
            document.removeEventListener('dragstart', this.boundHandlers.dragstart);
        }

        this.states.clear();
        this.activeSlider = null;
        this.boundHandlers = null;

        return this;
    }
};

DragScroller.init();