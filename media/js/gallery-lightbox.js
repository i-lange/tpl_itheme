/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

((document, Joomla) => {
    const gallerySelector = '.itheme-gallery';
    const triggerSelector = 'img[data-itheme-lightbox]';
    const activeGalleryClass = 'is-litelight-active-gallery';
    const imageSelector = `${gallerySelector}.${activeGalleryClass} ${triggerSelector}`;
    const lightboxClass = 'lite-light';
    let initialized = false;

    function getText(key, fallback) {
        return Joomla?.Text?._ ? Joomla.Text._(key, fallback) : fallback;
    }

    function localizeLightbox() {
        const lightbox = document.querySelector(`.${lightboxClass}`);

        if (!lightbox) {
            return;
        }

        lightbox.setAttribute('aria-label', getText('TPL_ITHEME_IMAGE_LIGHTBOX', 'Image viewer'));
        lightbox.querySelector('.lite-light-prev')?.setAttribute('aria-label', getText('TPL_ITHEME_PREV', 'Previous'));
        lightbox.querySelector('.lite-light-next')?.setAttribute('aria-label', getText('TPL_ITHEME_NEXT', 'Next'));
        lightbox.querySelector('.lite-light-close')?.setAttribute('aria-label', getText('TPL_ITHEME_CLOSE', 'Close'));
    }

    function prepareGallery(trigger) {
        const gallery = trigger.closest(gallerySelector);

        if (!gallery) {
            return;
        }

        document.querySelectorAll(`${gallerySelector}.${activeGalleryClass}`).forEach((item) => {
            item.classList.remove(activeGalleryClass);
        });

        gallery.classList.add(activeGalleryClass);
    }

    function handleClickCapture(event) {
        const trigger = event.target instanceof Element ? event.target.closest(triggerSelector) : null;

        if (!trigger) {
            return;
        }

        prepareGallery(trigger);
    }

    function handleKeydown(event) {
        if (event.key !== 'Enter' && event.key !== ' ') {
            return;
        }

        const trigger = event.target instanceof Element ? event.target.closest(triggerSelector) : null;

        if (!trigger) {
            return;
        }

        event.preventDefault();
        prepareGallery(trigger);
        trigger.click();
    }

    function initLightbox() {
        if (initialized || !window.LiteLight?.init) {
            return;
        }

        document.addEventListener('click', handleClickCapture, true);
        document.addEventListener('keydown', handleKeydown);

        window.LiteLight.init({
            imageSelector,
            imageUrlAttribute: 'data-itheme-lightbox',
            swipeThreshold: 60,
            fadeAnimationDuration: 180,
        });

        initialized = true;
        localizeLightbox();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLightbox);
    } else {
        initLightbox();
    }

    document.addEventListener('joomla:updated', initLightbox);
})(document, window.Joomla || {});
