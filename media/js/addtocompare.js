/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

const CompareButtonsManager = {
    /**
     * Точка входа:
     * вешаем один делегированный обработчик кликов на документ.
     * Это дешевле, чем навешивать обработчики на каждую кнопку,
     * и автоматически работает для динамически добавленных элементов.
     */
    init() {
        document.addEventListener('click', this.handleButtonClick.bind(this));
    },

    /**
     * Делегированный обработчик кликов по кнопкам сравнения.
     * Ищет ближайший элемент с атрибутом data-tocompare.
     */
    handleButtonClick(e) {
        const btn = e.target.closest('[data-tocompare]');
        if (!btn) return;

        e.preventDefault();
        this.processButtonClick(btn);
    },

    /**
     * Высокоуровневая логика:
     *  - product_id <= 0 → очистить список сравнения;
     *  - кнопка активна → удалить товар из сравнения;
     *  - иначе → добавить товар в сравнение.
     */
    processButtonClick(btn) {
        const productId = Number(btn.dataset.tocompare) || 0;

        if (productId <= 0) {
            this.clear(btn);
        } else if (btn.classList.contains('active')) {
            this.remove(productId, btn);
        } else {
            this.add(productId, btn);
        }
    },

    /**
     * Универсальный helper для POST‑запросов.
     * Сокращает дублирование кода, улучшает читабельность.
     *
     * @param {string} task   - серверная задача (add|remove|clear)
     * @param {Object} data   - тело запроса (ключ → значение)
     * @returns {Promise<Object>} - объект ответа {success, data, ... } либо выбрасывает ошибку
     */
    sendRequest(task, data = {}) {
        const url = `/index.php?option=com_ishop&controller=compare&task=${encodeURIComponent(task)}`;
        const formData = new FormData();

        Object.entries(data).forEach(([key, value]) => {
            formData.append(key, value);
        });

        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();

            xhr.open('POST', url, true);
            xhr.onreadystatechange = () => {
                if (xhr.readyState !== XMLHttpRequest.DONE) return;
                if (xhr.status !== 200) {
                    return reject(new Error(`HTTP status ${xhr.status}`));
                }
                try {
                    const response = JSON.parse(xhr.responseText);
                    resolve(response);
                } catch (err) {
                    reject(err);
                }
            };

            xhr.onerror = () => reject(new Error('Network error'));
            xhr.send(formData);
        });
    },

    /**
     * Добавление товара в список сравнения.
     */
    add(productId, btn) {
        this.sendRequest('add', { product_id: productId })
            .then(response => {
                if (!response || response.success !== true) return;
                try {
                    this.updateModule(response.data.count);
                    btn.classList.add('active');
                    // Отправляем событие в системы аналитики
                    if (typeof window.iTheme.setGoal !== 'undefined') {
                        window.iTheme.setGoal('TO_COMPARE', 'TO_COMPARE');
                    }
                } catch (err) {
                    console.log('Error [addToCompare]');
                }
            })
            .catch(err => {
                console.error('Compare add error:', err);
            });
    },

    /**
     * Удаление товара из списка сравнения.
     */
    remove(productId, btn) {
        this.sendRequest('remove', { product_id: productId })
            .then(response => {
                if (!response || response.success !== true) return;
                try {
                    this.updateModule(response.data.count);
                    btn.classList.remove('active');
                    // Отправляем событие в системы аналитики
                    if (typeof window.iTheme.setGoal !== 'undefined') {
                        window.iTheme.setGoal('REMOVE_FROM_COMPARE', 'REMOVE_FROM_COMPARE');
                    }
                } catch (err) {
                    console.log('Error goal [REMOVE_FROM_COMPARE]');
                }
            })
            .catch(err => {
                console.error('Compare remove error:', err);
            });
    },

    /**
     * Очищение списка сравнения.
     * Обнуляет модуль и сбрасывает состояние всех кнопок.
     */
    clear(btn) {
        this.sendRequest('remove', { product_id: 0 })
            .then(response => {
                if (!response || response.success !== true) return;
                try {
                    this.updateButtons();
                    this.updateModule(0);
                    btn.classList.remove('active');
                    // Отправляем событие в системы аналитики
                    if (typeof window.iTheme.setGoal !== 'undefined') {
                        window.iTheme.setGoal('CLEAR_COMPARE', 'CLEAR_COMPARE');
                    }
                } catch (err) {
                    console.log('Error goal [CLEAR_COMPARE]');
                }
            })
            .catch(err => {
                console.error('Compare clear error:', err);
            });
    },

    /**
     * Обновление модулей сравнения (счетчик и текст).
     * Ориентируется на атрибут data-module-compare.
     */
    updateModule(count) {
        const modules = document.querySelectorAll('[data-ishop-compare]');
        if (!modules.length) return;

        modules.forEach(module => {
            const counterEl = module.querySelector('small');

            if (counterEl) {
                counterEl.textContent = count;
            }

            if (count === 0) {
                module.classList.remove('active');
            } else if (!module.classList.contains('active')) {
                module.classList.add('active');
            }
        });
    },

    /**
     * Сброс состояния всех кнопок сравнения.
     * Здесь предполагается, что селектор именно такой.
     */
    updateButtons() {
        const buttons = document.querySelectorAll('[data-tocompare]');
        if (!buttons.length) return;

        buttons.forEach(btn => btn.classList.remove('active'));
    }
};

// Инициализация при загрузке страницы
CompareButtonsManager.init();