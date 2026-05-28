/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

(function () {
    class OffcanvasPanelNavigator {
        constructor(offcanvasEl, options = {}) {
            this.offcanvasEl = offcanvasEl;
            this.viewport = offcanvasEl.querySelector(options.viewportSelector || '[data-menu-viewport]');
            this.titleEl = offcanvasEl.querySelector(options.titleSelector || '.offcanvas-title');
            this.panelSelector = options.panelSelector || '[data-panel]';

            if (!this.viewport) return;

            this.panels = Array.from(this.viewport.querySelectorAll(this.panelSelector));
            this.panelMap = new Map(this.panels.map(panel => [panel.id, panel]));
            this.rootPanel = this.viewport.querySelector('[data-panel][data-root]') || this.panels[0] || null;

            if (!this.rootPanel) return;

            this.activePanelId = this.rootPanel.id;
            this.historyStack = [this.rootPanel.id];

            this.handleViewportClick = this.handleViewportClick.bind(this);
            this.handleShown = this.handleShown.bind(this);
            this.handleHidden = this.handleHidden.bind(this);

            this.viewport.addEventListener('click', this.handleViewportClick);
            this.offcanvasEl.addEventListener('shown.bs.offcanvas', this.handleShown);
            this.offcanvasEl.addEventListener('hidden.bs.offcanvas', this.handleHidden);

            this.reset();
        }

        getPanel(id) {
            return this.panelMap.get(id) || null;
        }

        getTitle(panelOrId) {
            const panel = typeof panelOrId === 'string' ? this.getPanel(panelOrId) : panelOrId;
            return panel?.dataset.title || 'Меню';
        }

        updateTitle() {
            if (this.titleEl) {
                this.titleEl.textContent = this.getTitle(this.activePanelId);
            }
        }

        buildPath(panelId) {
            const path = [];
            let current = this.getPanel(panelId);

            while (current) {
                path.unshift(current.id);
                const parentId = current.dataset.parent;
                if (!parentId) break;
                current = this.getPanel(parentId);
            }

            if (!path.length || path[0] !== this.rootPanel.id) {
                path.unshift(this.rootPanel.id);
            }

            return [...new Set(path)];
        }

        syncPanels(direction = 'forward') {
            this.panels.forEach(panel => {
                panel.classList.remove('is-active', 'is-prev', 'is-next');
                panel.setAttribute('aria-hidden', 'true');
            });

            const activeIndex = this.historyStack.indexOf(this.activePanelId);

            this.historyStack.forEach((id, index) => {
                const panel = this.getPanel(id);
                if (!panel) return;

                if (index < activeIndex) {
                    panel.classList.add('is-prev');
                } else if (index === activeIndex) {
                    panel.classList.add('is-active');
                    panel.setAttribute('aria-hidden', 'false');
                }
            });

            if (direction === 'back') {
                const forwardPanel = this.panels.find(panel =>
                    !this.historyStack.includes(panel.id) && panel.dataset.tempForward === 'true'
                );

                if (forwardPanel) {
                    forwardPanel.classList.add('is-next');
                    forwardPanel.dataset.tempForward = 'false';
                }
            }
        }

        openPanel(panelId, { silent = false } = {}) {
            const targetPanel = this.getPanel(panelId);
            if (!targetPanel || panelId === this.activePanelId) return false;

            this.historyStack.push(panelId);
            this.activePanelId = panelId;

            if (!silent) {
                this.syncPanels('forward');
                this.updateTitle();
            }

            return true;
        }

        setPanel(panelId) {
            const targetPanel = this.getPanel(panelId);
            if (!targetPanel) return false;

            this.panels.forEach(panel => {
                panel.dataset.tempForward = 'false';
            });

            this.historyStack = this.buildPath(panelId);
            this.activePanelId = panelId;

            this.syncPanels('forward');
            this.updateTitle();

            return true;
        }

        goBack() {
            if (this.historyStack.length <= 1) return false;

            const leavingId = this.historyStack.pop();
            const leavingPanel = this.getPanel(leavingId);

            if (leavingPanel) {
                leavingPanel.dataset.tempForward = 'true';
            }

            this.activePanelId = this.historyStack[this.historyStack.length - 1];
            this.syncPanels('back');
            this.updateTitle();

            return true;
        }

        reset() {
            this.historyStack = [this.rootPanel.id];
            this.activePanelId = this.rootPanel.id;

            this.panels.forEach(panel => {
                panel.scrollTop = 0;
                panel.dataset.tempForward = 'false';
            });

            this.syncPanels('forward');
            this.updateTitle();
        }

        show(panelId = null) {
            if (panelId) {
                this.setPanel(panelId);
            }

            const instance = bootstrap.Offcanvas.getOrCreateInstance(this.offcanvasEl);
            instance.show();
        }

        hide() {
            const instance = bootstrap.Offcanvas.getOrCreateInstance(this.offcanvasEl);
            instance.hide();
        }

        handleViewportClick(event) {
            const opener = event.target.closest('[data-panel-target]');
            if (opener && this.viewport.contains(opener)) {
                event.preventDefault();
                this.openPanel(opener.getAttribute('data-panel-target'));
                return;
            }

            const backBtn = event.target.closest('[data-panel-back]');
            if (backBtn && this.viewport.contains(backBtn)) {
                event.preventDefault();
                this.goBack();
            }
        }

        handleShown() {
            this.updateTitle();
            this.syncPanels('forward');
        }

        handleHidden() {
            this.reset();
        }

        destroy() {
            this.viewport.removeEventListener('click', this.handleViewportClick);
            this.offcanvasEl.removeEventListener('shown.bs.offcanvas', this.handleShown);
            this.offcanvasEl.removeEventListener('hidden.bs.offcanvas', this.handleHidden);
        }
    }

    class OffcanvasPanelsRegistry {
        constructor() {
            this.instances = new Map();
        }

        init(root = document) {
            const offcanvasList = root.querySelectorAll('[data-offcanvas-panels]');

            offcanvasList.forEach(offcanvasEl => {
                if (!offcanvasEl.id || this.instances.has(offcanvasEl.id)) return;
                this.instances.set(offcanvasEl.id, new OffcanvasPanelNavigator(offcanvasEl));
            });

            return this;
        }

        get(target) {
            if (!target) return null;

            if (target instanceof HTMLElement) {
                return this.instances.get(target.id) || null;
            }

            const id = String(target).replace(/^#/, '');
            return this.instances.get(id) || null;
        }

        open(offcanvasTarget, panelId = null) {
            const instance = this.get(offcanvasTarget);
            if (!instance) return null;

            instance.show(panelId);
            return instance;
        }

        reset(offcanvasTarget) {
            const instance = this.get(offcanvasTarget);
            if (!instance) return null;

            instance.reset();
            return instance;
        }
    }

    const registry = new OffcanvasPanelsRegistry();

    function initAutoTriggers() {
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-open-offcanvas-panel]');
            if (!trigger) return;

            const offcanvasTarget = trigger.getAttribute('data-offcanvas-target');
            const panelId = trigger.getAttribute('data-panel-target');

            if (!offcanvasTarget) return;

            event.preventDefault();
            registry.open(offcanvasTarget, panelId || null);
        });
    }

    window.OffcanvasPanels = registry;
    window.OffcanvasPanelNavigator = OffcanvasPanelNavigator;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            registry.init();
            initAutoTriggers();
        });
    } else {
        registry.init();
        initAutoTriggers();
    }
})();