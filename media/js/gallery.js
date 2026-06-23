/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

((document) => {
    const selector = '[data-itheme-gallery]';

    function syncThumbsAlignment(thumbs) {
        if (!thumbs) {
            return;
        }

        thumbs.classList.toggle('is-centered', thumbs.scrollWidth <= thumbs.clientWidth + 1);
    }

    function scrollActiveThumb(gallery) {
        const thumbs = gallery.querySelector('[data-itheme-gallery-thumbs]');
        const activeThumb = thumbs?.querySelector('.active');

        syncThumbsAlignment(thumbs);

        if (!thumbs || !activeThumb || thumbs.classList.contains('is-centered') || typeof thumbs.scrollTo !== 'function') {
            return;
        }

        const thumbsRect = thumbs.getBoundingClientRect();
        const activeRect = activeThumb.getBoundingClientRect();
        const activeCenter = activeRect.left - thumbsRect.left + thumbs.scrollLeft + activeRect.width / 2;
        const maxScroll = thumbs.scrollWidth - thumbs.clientWidth;
        const targetScroll = Math.max(0, Math.min(activeCenter - thumbs.clientWidth / 2, maxScroll));

        thumbs.scrollTo({
            left: targetScroll,
            behavior: 'smooth',
        });
    }

    function initGallery(gallery) {
        if (gallery.dataset.ithemeGalleryBound === '1') {
            scrollActiveThumb(gallery);
            return;
        }

        gallery.dataset.ithemeGalleryBound = '1';
        gallery.addEventListener('slid.bs.carousel', () => scrollActiveThumb(gallery));
        scrollActiveThumb(gallery);
    }

    function initGalleries(root = document) {
        if (root instanceof Element && root.matches(selector)) {
            initGallery(root);
        }

        if (root.querySelectorAll) {
            root.querySelectorAll(selector).forEach(initGallery);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initGalleries(document);
    });

    document.addEventListener('joomla:updated', (event) => {
        initGalleries(event.target instanceof Element ? event.target : document);
    });

    window.addEventListener('resize', () => {
        initGalleries(document);
    });
})(document);
