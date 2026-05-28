/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

const WishlistButtonsManager = {
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
     * Делегированный обработчик кликов по кнопкам избранного.
     * Ищет ближайший элемент с атрибутом data-towishlist.
     */
    handleButtonClick(e) {
        const btn = e.target.closest('[data-towishlist]');
        if (!btn) return;

        e.preventDefault();
        this.processButtonClick(btn);
    },

    /**
     * Высокоуровневая логика:
     *  - product_id <= 0 → очистить список избранного;
     *  - кнопка активна → удалить товар из избранного;
     *  - иначе → добавить товар в избранное.
     */
    processButtonClick(btn) {
        const productId = Number(btn.dataset.towishlist) || 0;

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
        const url = `/index.php?option=com_ishop&controller=wishlist&task=${encodeURIComponent(task)}`;
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
     * Добавление товара в список избранного.
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
                        window.iTheme.setGoal('TO_WISHLIST', 'TO_WISHLIST');
                    }
                } catch (err) {
                    console.log('Error [addToWishlist]');
                }
            })
            .catch(err => {
                console.error('Wishlist add error:', err);
            });
    },

    /**
     * Удаление товара из списка избранного.
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
                        window.iTheme.setGoal('REMOVE_FROM_WISHLIST', 'REMOVE_FROM_WISHLIST');
                    }
                } catch (err) {
                    console.log('Error [removeFromWishlist]');
                }
            })
            .catch(err => {
                console.error('Wishlist remove error:', err);
            });
    },

    /**
     * Очищение списка избранного.
     * Обнуляет модуль и сбрасывает состояние всех кнопок.
     */
    clear(btn) {
        this.sendRequest('clear')
            .then(response => {
                if (!response || response.success !== true) return;
                try {
                    this.updateButtons();
                    this.updateModule(0);
                    btn.classList.remove('active');

                    // Отправляем событие в системы аналитики
                    if (typeof window.iTheme.setGoal !== 'undefined') {
                        window.iTheme.setGoal('CLEAR_WISHLIST', 'CLEAR_WISHLIST');
                    }
                } catch (err) {
                    console.log('Error goal [CLEAR_WISHLIST]');
                }
            })
            .catch(err => {
                console.error('Wishlist clear error:', err);
            });
    },

    /**
     * Обновление модулей избранного (счетчик и текст).
     * Ориентируется на атрибут data-ishop-wishlist.
     */
    updateModule(count) {
        const modules = document.querySelectorAll('[data-ishop-wishlist]');
        if (!modules.length) return;

        modules.forEach(module => {
            const counterEl = module.querySelector('small');

            if (counterEl) {
                counterEl.textContent = count;
            }
        });
    },

    /**
     * Сброс состояния всех кнопок сравнения.
     * Здесь предполагается, что селектор именно такой.
     */
    updateButtons() {
        const buttons = document.querySelectorAll('[data-towishlist]');
        if (!buttons.length) return;

        buttons.forEach(btn => btn.classList.remove('active'));
    }
};

// Инициализация при загрузке страницы
WishlistButtonsManager.init();