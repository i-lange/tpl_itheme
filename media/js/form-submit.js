(function () {
	'use strict';

	var SELECTORS = {
		forms: '[data-iform]',
		thankYou: '.form_thank_you'
	};

	function onReady(callback) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', callback, { once: true });
		} else {
			callback();
		}
	}

	function waitFor(condition, callback, options) {
		options = options || {};
		var timeout = options.timeout || 10000;
		var interval = options.interval || 100;
		var startedAt = Date.now();

		function check() {
			var result = false;

			try {
				result = condition();
			} catch (e) {}

			if (result) {
				callback(result);
				return;
			}

			if (Date.now() - startedAt >= timeout) {
				return;
			}

			setTimeout(check, interval);
		}

		check();
	}

	function getCsrfTokenName(form) {
		var inputs = form.querySelectorAll('input[type="hidden"][value="1"]');
		var tokenName = '';

		inputs.forEach(function (input) {
			if (input.name && input.name.length === 32) {
				tokenName = input.name;
			}
		});

		return tokenName;
	}

	function iformUpdate(form, data) {
		if (!data || !data.data) {
			return;
		}

		var errors = Array.isArray(data.data.errors) ? data.data.errors : [];
		var valid = Array.isArray(data.data.valid) ? data.data.valid : [];

		valid.forEach(function (fieldName) {
			var validInput = form.querySelector('[name="' + fieldName + '"]');
			if (validInput) {
				validInput.classList.remove('is-invalid');
				validInput.classList.add('is-valid');
			}
		});

		errors.forEach(function (error) {
			if (!error || error.key === 'header') {
				return;
			}

			var invalidInput = form.querySelector('[name="' + error.key + '"]');
			if (invalidInput) {
				invalidInput.classList.remove('is-valid');
				invalidInput.classList.add('is-invalid');
			}
		});
	}

	function showThankYou(form) {
		var parent = form.parentNode;
		if (!parent) {
			return;
		}

		var thankYou = parent.querySelector(SELECTORS.thankYou);
		if (thankYou) {
			form.classList.add('d-none');
			thankYou.classList.add('active');
		}
	}

	function iformProcess(form) {
		if (!window.iTheme || typeof window.iTheme.Validate !== 'function') {
			console.error('iTheme.Validate is not available');
			return;
		}

		if (form.dataset.loading === '1') {
			return;
		}

		var hasErrors = window.iTheme.Validate(form);
		if (hasErrors) {
			return;
		}

		form.dataset.loading = '1';

		var submitButton = form.querySelector('[type="submit"]');
		var spinner = null;

		if (submitButton) {
			spinner = document.createElement('i');
			spinner.className = 'spinner';
			submitButton.appendChild(spinner);
		}

		var formData = new FormData(form);
		var tokenName = getCsrfTokenName(form);

		var xhr = new XMLHttpRequest();
		xhr.open('POST', '?option=com_ajax&module=iform&format=json', true);

		if (tokenName) {
			xhr.setRequestHeader('X-CSRF-Token', tokenName);
		}

		xhr.onreadystatechange = function () {
			if (xhr.readyState !== XMLHttpRequest.DONE) {
				return;
			}

			form.dataset.loading = '0';

			if (spinner) {
				spinner.remove();
			}

			if (xhr.status !== 200) {
				console.error('Form request failed with status:', xhr.status);
				return;
			}

			var data;
			try {
				data = JSON.parse(xhr.responseText);
			} catch (e) {
				console.error('Invalid JSON response', e);
				return;
			}

			iformUpdate(form, data);

			var errors = data && data.data && Array.isArray(data.data.errors) ? data.data.errors : [];
			if (errors.length !== 0) {
				form.classList.remove('d-none');
				return;
			}

			try {
				if (window.iTheme && typeof window.iTheme.setEcommerce === 'function') {
					window.iTheme.setEcommerce('purchase', formData.get('item_id'), 1);
				}
			} catch (e) {
				console.error('Ecommerce tracking error', e);
			}

			showThankYou(form);
		};

		xhr.send(formData);
	}

	function bindForm(form) {
		if (!form || form.dataset.iformBound === '1') {
			return;
		}

		form.dataset.iformBound = '1';

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			event.stopPropagation();
			iformProcess(form);
		});
	}

	function bindForms() {
		document.querySelectorAll(SELECTORS.forms).forEach(function (form) {
			bindForm(form);
		});
	}

	function init() {
		waitFor(
			function () {
				return document.querySelector(SELECTORS.forms) &&
					window.iTheme &&
					typeof window.iTheme.Validate === 'function';
			},
			function () {
				bindForms();
			},
			{
				timeout: 15000,
				interval: 100
			}
		);
	}

	onReady(function () {
		init();

		var observer = new MutationObserver(function () {
			if (window.iTheme && typeof window.iTheme.Validate === 'function') {
				bindForms();
			}
		});

		observer.observe(document.documentElement, {
			childList: true,
			subtree: true
		});
	});
})();