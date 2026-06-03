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


    document.addEventListener('DOMContentLoaded', event => {
        // Инициализация
        initTemplate(event);

        // Переход на главную
        const lagoHandler = new FrontpageRedirect();


        // Ленивая загрузка стилей css
        document.head.querySelectorAll('link[rel="lazy-stylesheet"]').forEach($link => {
            $link.rel = 'stylesheet';
        });


        if (typeof window.lozad !== 'function') {
            console.error('Lozad не загружен');
        } else {
            // Ленивая загрузка изображений и фреймов
            window.lozad('img[data-srcset]', {
                rootMargin: '300px 500px',
                threshold: 0.1,
                enableAutoReload: true
            }).observe();

            // window.lozad('iframe[data-src]', {
            //     rootMargin: '10px 0px',
            //     threshold: 0.1,
            //     enableAutoReload: true
            // }).observe();
        }

        // блоки с прокруткой
        document.querySelectorAll('.scroll-items-list').forEach((list) => {
            let isDown = false;
            let startX = 0;
            let scrollLeft = 0;

            list.addEventListener('mousedown', (e) => {
                isDown = true;
                list.classList.add('is-dragging');
                startX = e.pageX - list.offsetLeft;
                scrollLeft = list.scrollLeft;
            });

            list.addEventListener('mouseleave', () => {
                isDown = false;
                list.classList.remove('is-dragging');
            });

            list.addEventListener('mouseup', () => {
                isDown = false;
                list.classList.remove('is-dragging');
            });

            list.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - list.offsetLeft;
                const walk = x - startX;
                list.scrollLeft = scrollLeft - walk;
            });

            list.addEventListener('dragstart', (e) => {
                e.preventDefault();
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.js-finder-searchform');
        if (!form) return;

        const input = form.querySelector('.js-finder-search-query');
        const clearBtn = form.querySelector('.btn-close');

        if (clearBtn && input) {
            clearBtn.addEventListener('click', function () {
                input.value = '';
                input.focus();
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
        }
    });

    // Инициализируется при обновлении части страницы
    document.addEventListener('joomla:updated', initTemplate);

})(Joomla, document);

import './__submit.js';
import './__dragscroller.js';