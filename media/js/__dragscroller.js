const DragScroller = {
    states: new Map(),
    activeSlider: null,
    scrollSpeed: 1,

    // Сохраняем ссылки на bound функции
    boundHandlers: null,

    init(selector = '[data-drag-scroller]', scrollSpeed = 1) {
        this.scrollSpeed = scrollSpeed;

        // Создаём bound функции один раз
        this.boundHandlers = {
            mousedown: this.handleMouseDown.bind(this),
            mousemove: this.handleMouseMove.bind(this),
            mouseup: this.handleMouseUp.bind(this)
        };

        this.registerSliders(selector);

        document.addEventListener('mousedown', this.boundHandlers.mousedown, { passive: false });
        document.addEventListener('mousemove', this.boundHandlers.mousemove, { passive: false });
        document.addEventListener('mouseup', this.boundHandlers.mouseup);

        return this;
    },

    registerSliders(selector) {
        const sliders = document.querySelectorAll(selector);
        sliders.forEach(slider => {
            if (!this.states.has(slider)) {
                this.states.set(slider, {
                    isDown: false,
                    startX: 0,
                    scrollLeft: 0
                });
            }
        });
    },

    handleMouseDown(e) {
        const slider = e.target.closest('[data-drag-scroller]') ||
            Array.from(this.states.keys()).find(s => s.contains(e.target));

        if (!slider) return;
        this.startDrag(slider, e);
    },

    handleMouseMove(e) {
        if (!this.activeSlider) return;
        e.preventDefault();
        this.processDrag(this.activeSlider, e);
    },

    handleMouseUp() {
        this.stopDrag();
    },

    startDrag(slider, e) {
        const state = this.states.get(slider);
        if (!state) return;

        state.isDown = true;
        state.startX = e.pageX - slider.offsetLeft;
        state.scrollLeft = slider.scrollLeft;
        this.activeSlider = slider;
    },

    processDrag(slider, e) {
        const state = this.states.get(slider);
        if (!state || !state.isDown) return;

        const x = e.pageX - slider.offsetLeft;
        const walk = (x - state.startX) * this.scrollSpeed;
        slider.scrollLeft = state.scrollLeft - walk;
    },

    stopDrag() {
        if (!this.activeSlider) return;
        const state = this.states.get(this.activeSlider);
        if (state) {
            state.isDown = false;
        }
        this.activeSlider = null;
    },

    refresh(selector = '[data-drag-scroller]') {
        this.registerSliders(selector);
        return this;
    },

    destroy() {
        if (this.boundHandlers) {
            document.removeEventListener('mousedown', this.boundHandlers.mousedown);
            document.removeEventListener('mousemove', this.boundHandlers.mousemove);
            document.removeEventListener('mouseup', this.boundHandlers.mouseup);
        }
        this.states.clear();
        this.activeSlider = null;
        return this;
    }
};

DragScroller.init();