/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

const sliders = document.querySelectorAll('.range');

sliders.forEach(slider => {
    const rangeMinInput = slider.querySelector('.range-min');
    const rangeMaxInput = slider.querySelector('.range-max');
    const rangeSlider = slider.querySelector('.range-slider');
    const rangeLine = slider.querySelector('.range-slider__line');
    const rangeUpperPoint = slider.querySelector('.range-slider__point--upper');
    const rangeLowerPoint = slider.querySelector('.range-slider__point--lower');

    const minPrice = parseInt(rangeMinInput.min);
    const maxPrice = parseInt(rangeMaxInput.max);

    let inputTimeout;

    function updateSlider() {
        const minValue = parseInt(rangeMinInput.value) || minPrice;
        const maxValue = parseInt(rangeMaxInput.value) || maxPrice;

        const minPercent = ((minValue - minPrice) / (maxPrice - minPrice)) * 100;
        const maxPercent = ((maxValue - minPrice) / (maxPrice - minPrice)) * 100;

        rangeLine.style.left = minPercent + '%';
        rangeLine.style.right = (100 - maxPercent) + '%';

        rangeUpperPoint.style.left = minPercent + '%';
        rangeLowerPoint.style.left = maxPercent + '%';

        // Ensure points do not overlap
        if (minValue === maxValue) {
            rangeUpperPoint.style.zIndex = 1;
            rangeLowerPoint.style.zIndex = 2;
        } else {
            rangeUpperPoint.style.zIndex = '';
            rangeLowerPoint.style.zIndex = '';
        }
    }

    function handleInputChange() {
        clearTimeout(inputTimeout);
        inputTimeout = setTimeout(() => {
            let minValue = parseInt(rangeMinInput.value);
            let maxValue = parseInt(rangeMaxInput.value);

            if (minValue < minPrice || isNaN(minValue)) {
                rangeMinInput.value = '';
                minValue = minPrice;
            }

            if (maxValue > maxPrice || isNaN(maxValue)) {
                rangeMaxInput.value = '';
                maxValue = maxPrice;
            }

            if (minValue > maxValue) {
                minValue = maxValue;
                rangeMinInput.value = minValue;
            }

            updateSlider();
        }, 500); // Задержка в 500 мс
    }

    function handleSliderMove(event, point) {
        const sliderRect = rangeSlider.getBoundingClientRect();
        const sliderWidth = sliderRect.width;
        const pointWidth = rangeUpperPoint.offsetWidth;
        const offsetX = (event.touches ? event.touches[0].clientX : event.clientX) - sliderRect.left;

        let newPercent = (offsetX / sliderWidth) * 100;
        newPercent = Math.max(0, Math.min(100, newPercent));

        const newValue = Math.round(minPrice + (newPercent / 100) * (maxPrice - minPrice));

        if (point === rangeUpperPoint) {
            const maxValue = parseInt(rangeMaxInput.value) || maxPrice;
            const maxPercent = ((maxValue - minPrice) / (maxPrice - minPrice)) * 100;
            if (newPercent > maxPercent - (pointWidth / sliderWidth) * 100) {
                rangeMinInput.value = Math.round(minPrice + (maxPercent - (pointWidth / sliderWidth) * 100) / 100 * (maxPrice - minPrice));
            } else {
                rangeMinInput.value = newValue;
            }
            if (newValue === minPrice) {
                rangeMinInput.value = '';
            }
        } else {
            const minValue = parseInt(rangeMinInput.value) || minPrice;
            const minPercent = ((minValue - minPrice) / (maxPrice - minPrice)) * 100;
            if (newPercent < minPercent + (pointWidth / sliderWidth) * 100) {
                rangeMaxInput.value = Math.round(minPrice + (minPercent + (pointWidth / sliderWidth) * 100) / 100 * (maxPrice - minPrice));
            } else {
                rangeMaxInput.value = newValue;
            }
            if (newValue === maxPrice) {
                rangeMaxInput.value = '';
            }
        }

        updateSlider();
    }

    function addEventListeners(point) {
        point.addEventListener('mousedown', function (event) {
            function onMouseMove(event) {
                handleSliderMove(event, point);
            }

            document.addEventListener('mousemove', onMouseMove);

            document.addEventListener('mouseup', function () {
                document.removeEventListener('mousemove', onMouseMove);
            }, { once: true });
        });

        point.addEventListener('touchstart', function (event) {
            function onTouchMove(event) {
                handleSliderMove(event, point);
            }

            document.addEventListener('touchmove', onTouchMove);

            document.addEventListener('touchend', function () {
                document.removeEventListener('touchmove', onTouchMove);
            }, { once: true });
        });
    }

    rangeMinInput.addEventListener('input', handleInputChange);
    rangeMaxInput.addEventListener('input', handleInputChange);

    addEventListeners(rangeUpperPoint);
    addEventListeners(rangeLowerPoint);

    updateSlider();
});
