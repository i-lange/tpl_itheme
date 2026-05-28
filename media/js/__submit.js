{
	// отправка формы заказа
	const iforms = document.querySelectorAll('[data-iform]');
	if (iforms.length > 0) {
		iforms.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				event.preventDefault();
				event.stopPropagation();
				iformProcess(form);
			});
		});
	}
}

// Обрабатываем форму
function iformProcess(form) {
	//const button = form.querySelector('[type="submit"]');
	const tokens = form.querySelectorAll('[value="1"]');
	let jtoken = '';
	const form_data = new FormData(form);

	//ищем токен формы
	tokens.forEach(function (input) {
		if (input.type === 'hidden' && input.name.length === 32) {
			jtoken = input.name;
		}
	});

	// если поля заполнены верно
	if (!window.iTheme.Validate(form)) {
		const xhr = new XMLHttpRequest();
		xhr.open('POST', '?option=com_ajax&module=iform&format=json', true);
		xhr.setRequestHeader('X-CSRF-Token', jtoken);
		//xhr.setRequestHeader('Cache-Control', 'no-cache');

		xhr.addEventListener("readystatechange", () => {
			if (xhr.readyState !== XMLHttpRequest.DONE) {
				return;
			}

			if (xhr.status === 200) {
				//console.log(xhr.responseText);
				const data = JSON.parse(xhr.responseText);
				//console.log(data);
				iformUpdate(form, data);

				if (data.data.errors.length === 0) {
					// Электронная коммерция
					if (typeof window.iTheme.setEcommerce !== 'undefined') {
						window.iTheme.setEcommerce('purchase', form_data.get('item_id'), 1);
					}

					//form.reset();
					form.classList.add('d-none');
					form.parentNode.querySelector('.form_thank_you').classList.add('active');
				} else {
					form.classList.remove('d-none');
				}
			}
		});
		xhr.send(form_data);
	}
}

// Обновляем статусы полей в форме
function iformUpdate(form, data) {
	if (data.data.errors.length !== 0) {
		data.data.errors.forEach(function (error) {
			if (error.key !== 'header') {
				const input = form.querySelector('[name="' + error.key + '"]');
				input.classList.add('is-invalid');
			}
		});
	}

	if (data.data.valid.length !== 0) {
		data.data.valid.forEach(function (valid) {
			const input = form.querySelector('[name="' + valid + '"]');
			input.classList.add('is-valid');
		});
	}
}