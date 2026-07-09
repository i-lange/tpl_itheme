/*
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

(function (window, document) {
    'use strict';

    window.iTheme = window.iTheme || {};
    function Validate(form) {
        let error = false;
        const emailPattern=/@/;
        const phonePattern=/[a-zа-яё]+/i;

        // выбираем все обязательные поля
        const fields = form.querySelectorAll('input[required]');
        fields.forEach(function(input) {
            if (input.value === '') {
                input.classList.add('is-invalid');
                error = true;
            }
            else {
                input.classList.remove('is-invalid');
            }
        });

        if(!error) {
            const emails = form.querySelectorAll('input[type=email]');
            emails.forEach(function(email) {
                const value = email.value.trim();

                if (value === '' && !email.required) {
                    email.classList.remove('is-invalid');
                    return;
                }

                if (emailPattern.test(value)) {
                    email.classList.remove('is-invalid');
                }
                else {
                    email.classList.add('is-invalid');
                    error = true;
                }
            });
        }

        if(!error) {
            // выбираем все обязательные Phone-поля
            const phones = form.querySelectorAll('input[type=tel]');
            phones.forEach(function(phone) {
                if (phonePattern.test(phone.value)) {
                    phone.classList.add('is-invalid');
                    error = true;
                }
                else {
                    phone.classList.remove('is-invalid');
                }
            });
        }

        return error;
    }

    window.iTheme.Validate = Validate;
})(window, document);
