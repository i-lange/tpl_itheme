/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */
(function (root) {
    var DIGIT = "9";
    var ALPHA = "A";
    var ALPHANUM = "S";

    // Коды клавиш, при которых не трогаем значение (Shift, Ctrl, стрелки и т.п.)
    var BY_PASS_KEYS = [9, 16, 17, 18, 36, 37, 38, 39, 40, 91, 92, 93];

    function isAllowedKeyCode(keyCode) {
        for (var i = 0, len = BY_PASS_KEYS.length; i < len; i++) {
            if (keyCode === BY_PASS_KEYS[i]) {
                return false;
            }
        }
        return true;
    }

    // Логика форматирования по паттерну — урезанная версия VMasker.toPattern
    function toPattern(value, pattern) {
        var patternChars = pattern.replace(/\W/g, "");
        var output = pattern.split("");
        var values = value.toString().replace(/\W/g, "");
        var charsValues = values.replace(/\W/g, "");
        var index = 0;
        var i;
        var outputLength = output.length;

        for (i = 0; i < outputLength; i++) {
            // Вход закончился
            if (index >= values.length) {
                if (patternChars.length === charsValues.length) {
                    return output.join("");
                } else {
                    break;
                }
            } else {
                // Остались символы во входе
                if (
                    (output[i] === DIGIT && /[0-9]/.test(values[index])) ||
                    (output[i] === ALPHA && /[a-zA-Z]/.test(values[index])) ||
                    (output[i] === ALPHANUM && /[0-9a-zA-Z]/.test(values[index]))
                ) {
                    output[i] = values[index++];
                } else if (
                    output[i] === DIGIT ||
                    output[i] === ALPHA ||
                    output[i] === ALPHANUM
                ) {
                    // Если ожидаем спец-символ маски, а во входе что‑то не то — обрываем
                    return output.slice(0, i).join("");
                }
            }
        }

        return output.join("").substr(0, i);
    }

    // "Класс" для работы с набором элементов
    function Masker(elements) {
        this.elements = elements;
    }

    // Единственный публичный метод: навесить маску по паттерну
    Masker.prototype.maskPattern = function (pattern) {
        var elements = this.elements;

        var onType = function (e) {
            e = e || window.event;
            var source = e.target || e.srcElement;

            if (!source) return;
            if (!isAllowedKeyCode(e.keyCode)) return;

            setTimeout(function () {
                source.value = toPattern(source.value, pattern);
            }, 0);
        };

        for (var i = 0, len = elements.length; i < len; i++) {
            var el = elements[i];
            el.onkeyup = onType;

            if (el.value && el.value.length) {
                el.value = toPattern(el.value, pattern);
            }
        }
    };

    // Фабрика: оборачивает элемент(ы) в Masker
    function PhoneMasker(el) {
        if (!el) {
            throw new Error("PhoneMasker: no element to bind.");
        }
        var elements = "length" in el ? (el.length ? el : []) : [el];
        return new Masker(elements);
    }

    // Экспорт в глобальный объект (window.PhoneMasker)
    root.PhoneMasker = PhoneMasker;
})(typeof window !== "undefined" ? window : this);

// Пример использования (можете удалить или изменить под себя):
// const phones = document.querySelectorAll("input.required-phone");
// PhoneMasker(phones).maskPattern("+999 (99) 999-99-99-999");

const phones = document.querySelectorAll("input.required-phone");
window.PhoneMasker(phones).maskPattern("+999 (99) 999-99-99-999");