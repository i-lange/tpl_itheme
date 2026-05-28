/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('#header');
    const main = document.querySelector('#main');

    if (!header || !main) {
        console.warn('Sticky header: required elements not found');
        return;
    }

    let lastScrollY = Math.max(window.scrollY, 0);
    let ticking = false;
    let headerTrigger = 0;
    const delta = 8;

    function measureHeaderHeight() {
        header.classList.remove('is-hidden', 'is-compact');

        const fullHeight = header.offsetHeight;
        headerTrigger = fullHeight;

        document.documentElement.style.setProperty('--header-offset', `${fullHeight}px`);
    }

    function resetHeader() {
        header.classList.remove('is-hidden', 'is-compact');
    }

    function hideHeader() {
        header.classList.add('is-compact');
        header.classList.add('is-hidden');
    }

    function showHeader() {
        header.classList.add('is-compact');
        header.classList.remove('is-hidden');
    }

    function updateHeader() {
        const currentScrollY = Math.max(window.scrollY, 0);
        const diff = currentScrollY - lastScrollY;

        if (currentScrollY <= 0) {
            resetHeader();
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        if (currentScrollY <= headerTrigger) {
            resetHeader();
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        if (Math.abs(diff) < delta) {
            ticking = false;
            return;
        }

        if (diff > 0) {
            hideHeader();
        } else {
            showHeader();
        }

        lastScrollY = currentScrollY;
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(updateHeader);
    }, { passive: true });

    window.addEventListener('resize', () => {
        measureHeaderHeight();
        updateHeader();
    });

    measureHeaderHeight();
    updateHeader();
});