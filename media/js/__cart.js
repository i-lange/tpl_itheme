const iShoppingCart = {
	init() {
		this.iShoppingCartForm = document.getElementById('cart-submit');
		if (this.iShoppingCartForm) {
			// Элементы корзины для вставки данных
			this.cartTotal = this.iShoppingCartForm.querySelector('[data-cart-total]');
			this.cartTotalDiscount = this.iShoppingCartForm.querySelector('[data-cart-total-discount]');
			this.cartSummary = this.iShoppingCartForm.querySelector('[data-cart-summary]');
			this.carts = document.querySelectorAll('[data-module-cart]');

			// Обработчики
			this.iShoppingCartForm.addEventListener('click', this.handleButtonClick.bind(this));
			this.iShoppingCartForm.addEventListener('change', this.handleCheckboxClick.bind(this));
		}
	},

	// Обработчик для кнопок
	handleButtonClick(e) {
		const btn = e.target.closest('[data-cart-button]');
		if (!btn) return;

		this.processButtonClick(e, btn);
	},

	// Обработчик для чекбоксов
	handleCheckboxClick(e) {
		if (e.target.matches('input[name="products[]"]')) {
			this.processCheckboxClick(e);
		}
	},

	// Общая обработка клика по кнопке
	processButtonClick(e, btn) {
		e.preventDefault();
		const product = btn.closest('[data-product-incart-id]')
		if (!product) return;

		const product_id = Number(product.dataset.productIncartId);
		if (product_id <= 0) return;

		const type = btn.dataset.cartButton;

		if (type === 'plus') {
			// Увеличиваем количество
			this.changeQuantity(product_id, 1, product);
		} else if (type === 'minus') {
			// Уменьшаем количество
			if (this.getCount(btn) > 1) {
				this.changeQuantity(product_id, -1, product);
			} else {
				// Удаляем товар из корзины
				this.removeProduct(product_id, product);
			}
		} else {
			// Удаляем товар из корзины
			this.removeProduct(product_id, product);
		}
	},

	// Общая обработка клика по чекбоксу
	async processCheckboxClick(e) {
		// Собираем все выбранные значения
		const selectedProducts = Array.from(
			this.iShoppingCartForm.querySelectorAll('input[name="products[]"]:checked')
		).map(checkbox => checkbox.value);

		if (selectedProducts.length === 0) {
			this.setCartInfo({total: 0, total_discount: 0, summary: 0});
			return;
		}

		// Создаем FormData для отправки
		const params = new FormData();
		selectedProducts.forEach(product => {
			params.append('filter_products[]', product);
		});

		try {
			const response = await this.request('reload', params);
			console.log(response);
			// Проверяем успешность ответа сервера перед обновлением
			if (response.success) {
				this.setCartInfo(response.data);
			} else {
				console.error('Ошибка при удалении товара:', response.message);
			}
		} catch (error) {
			console.error('Ошибка при выполнении запроса:', error);
		}
	},

	// Получение текущего количества товара в корзине
	getCount(btn) {
		const input = btn.parentNode.querySelector('[data-quantity]');
		if (input) {
			return input.value;
		}

		return 0;
	},

	// Установка новых данных товара в корзине
	setProductInfo(product_id, product, data) {
		// Текущее количество
		const input = product.querySelector('[data-quantity]');
		if (input) {
			input.value = data.products[product_id].count;
		}
		// Текущая цена
		const price = product.querySelector('[data-price]');
		if (price) {
			price.textContent = data.products[product_id].incart_total.toLocaleString('ru-RU');
		}
		// Текущая старая цена
		const oldPrice = product.querySelector('[data-old-price]');
		if (oldPrice) {
			oldPrice.textContent = data.products[product_id].incart_old_total.toLocaleString('ru-RU');
		}
	},

	// Установка новых данных корзины
	setCartInfo(data) {
		// Сумма по корзине без скидки
		if (this.cartTotal) {
			this.cartTotal.textContent = data.total.toLocaleString('ru-RU');
		}

		// Сумма скидок по корзине
		if (this.cartTotalDiscount) {
			this.cartTotalDiscount.textContent = data.total_discount.toLocaleString('ru-RU');
		}

		// Итого по корзине
		if (this.cartSummary) {
			this.cartSummary.textContent = data.summary.toLocaleString('ru-RU');
		}

		// Обновляем количество в модулях корзины
		if (this.carts) {
			this.carts.forEach(cart => {
				cart.querySelector('small').textContent = data.count;
				if (typeof textProducts !== 'undefined') {
					cart.querySelector('span').textContent =
						data.count + ' ' + declension(data.count, textProducts);
				}
			});
		}
	},

	// Удаление товара
	async removeProduct(product_id, product) {
		const params = new FormData();
		params.append('product_id', product_id);

		try {
			const response = await this.request('remove', params);
			//console.log(response);
			// Проверяем успешность ответа сервера перед удалением
			if (response.success) {
				this.setCartInfo(response.data);
				product.remove();
				// Электронная коммерция
				eCommerceCart('remove_from_cart', product_id, 1);
			} else {
				console.error('Ошибка при удалении товара:', response.message);
			}
		} catch (error) {
			console.error('Ошибка при выполнении запроса:', error);
		}
	},

	// Изменение количества товара
	async changeQuantity(product_id, delta, product) {
		const params = new FormData();
		params.append('product_id', product_id);
		params.append('quantity', delta);

		try {
			const response = await this.request('change', params);
			//console.log(response);
			// Проверяем успешность ответа сервера перед обновлением
			if (response.success) {
				this.setProductInfo(product_id, product, response.data);
				this.setCartInfo(response.data);

				// Электронная коммерция
				if (delta > 0) {
					eCommerceCart('add_to_cart', product_id, 1);
				} else {
					eCommerceCart('remove_from_cart', product_id, 1);
				}
			} else {
				console.error('Ошибка при удалении товара:', response.message);
			}
		} catch (error) {
			console.error('Ошибка при выполнении запроса:', error);
		}
	},

	request(task, params) {
		return new Promise((resolve, reject) => {
			const url = '/index.php?option=com_ishop&controller=cart&task=' + task;
			const request = new XMLHttpRequest();

			request.open('POST', url, true);
			request.addEventListener('load', () => {
				if (request.status === 200) {
					try {
						resolve(JSON.parse(request.responseText));
					} catch (e) {
						reject(new Error('Ошибка парсинга JSON'));
					}
				} else {
					reject(new Error(`Ошибка HTTP: ${request.status}`));
				}
			});

			request.addEventListener('error', () => {
				reject(new Error('Ошибка сети'));
			});

			request.send(params);
		});
	}
};

// Инициализация при загрузке страницы
iShoppingCart.init();