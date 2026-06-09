# AGENTS.md

## Контекст

`tpl_itheme` - устанавливаемый site-template для Joomla 6 (`client="site"`, `method="upgrade"`), основной frontend-шаблон магазина `magazin-gefest-new.local`. Шаблон обслуживает стандартный контент Joomla и e-commerce на базе `com_ishop` с модулями/плагинами iShop.

Локальный сайт:
- frontend: `https://magazin-gefest-new.local`
- admin: `https://magazin-gefest-new.local/administrator/`
- Windows root сайта: `C:\OSPanel\home\magazin-gefest-new.local\`

Связанные расширения в `C:\OSPanel\home\`: `com_ishop`, `com_ishopintegro`, `mod_ishop_cart`, `mod_ishop_compare`, `mod_ishop_filter`, `mod_ishop_zone`, `plg_ishopfinder`, `plg_ishopintegrocron`, `plg_ithemecsscompiler`, `tpl_itheme`. При изменениях учитывайте их зависимости и Web Asset Manager assets.

## Документация

Если задача касается API/CLI/настроек библиотек, фреймворков, SDK, Joomla или Bootstrap, используйте Context7: сначала `resolve-library-id`, затем `query-docs`. Для Joomla 6 особенно важны Templates, Template Details File и Web Asset Manager из официальной документации.

## Ключевые файлы

- `templateDetails.xml` - источник правды для установки: файлы, media, позиции, языки, параметры, update server.
- `index.php`, `component.php`, `error.php`, `offline.php` - entrypoints шаблона.
- `head.php`, `header.php`, `footer.php`, `analytics.php` - общая разметка, meta, assets, аналитика.
- `joomla.asset.json` - assets шаблона для Joomla Web Asset Manager.
- `html/` - overrides `com_content`, `com_finder`, `com_ishop`, `com_users`, модулей и layouts.
- `media/scss/` - SCSS исходники; `itheme.scss` импортирует Bootstrap и блоки шаблона.
- `media/js/` - JS исходники и сгенерированные `.min.js/.gz`.
- `media/css/` - сгенерированные CSS, `.min.css/.gz`; руками не править.
- `language/en-GB`, `language/ru-RU` - языковые строки.
- `build.mjs`, `pack.mjs`, `vite.config.*.mts` - сборка и упаковка zip.

## Стек

- Joomla CMS 6.x, PHP 8.3+.
- Bootstrap 5.3 SCSS лежит в `media/scss/vendor/bootstrap`; JS Bootstrap подключайте только Joomla asset names (`bootstrap.dropdown`, `bootstrap.modal`, `bootstrap.carousel`, `bootstrap.offcanvas` и т.п.).
- Frontend JS: vanilla JavaScript, `Joomla`, `DOMContentLoaded`, `joomla:updated`.
- Node.js `>=24`, npm `>=11.8`, pnpm `>=10.3`; Vite 8, Sass, Lightning CSS, vite-plugin-compression.
- Composer-зависимостей в шаблоне нет.

## Команды

- `pnpm install` - установить JS-зависимости.
- `pnpm build` - собрать CSS и JS.
- `pnpm build:css` - собрать `media/css/itheme.css`, `.min.css`, `.gz`.
- `pnpm build:js` - собрать перечисленные JS entrypoints в `.min.js/.gz`.
- `pnpm watch:css`, `pnpm watch:js` - watch-сборка.
- `pnpm test` - заглушка `No automated tests yet`.
- `pnpm zip` - `pnpm build` + `tpl_itheme-{version}.zip`.

## Правила изменений

- Меняйте исходники, не сборочные артефакты: SCSS в `media/scss`, JS entrypoints в `media/js`, PHP в корне или `html/`.
- После правок SCSS/JS запускайте нужную сборку; для installable результата включайте сгенерированные файлы.
- `vite.config.css.mts` очищает `media/css` (`emptyOutDir: true`), поэтому не храните там ручные файлы.
- Новые корневые PHP/XML/JSON файлы добавляйте и в `templateDetails.xml`, и в `pack.mjs`; подпапки `html`, `language`, `media` пакуются целиком.
- Новые позиции модулей сначала добавляйте в `<positions>`, затем используйте в `<jdoc:include>`.
- Новые параметры шаблона добавляйте в `<config><fields name="params">` с `type`, `filter`/`validate`, `default`, `showon` при необходимости и языковыми ключами.
- CSS/JS подключайте через `joomla.asset.json` и `$wa->useStyle()`, `$wa->useScript()`, `$wa->usePreset()`; прямые `<script>/<link>` допускаются только с явной причиной.
- При новом JS entrypoint обновите `JS_ENTRY_FILES` в `vite.config.js.mts` и `joomla.asset.json`; при новом SCSS entrypoint - `SCSS_ENTRIES` в `vite.config.css.mts` и assets.
- Сверяйте `joomla.asset.json`, реальные файлы `media/js|css` и Vite entrypoints: декларация asset не должна ссылаться на отсутствующий файл.
- Overrides держите в `html/com_*`, `html/mod_*`; переиспользуемую разметку - в `html/layouts/*` через `LayoutHelper::render()`.
- В PHP сохраняйте `defined('_JEXEC') or die;`, namespaced Joomla API (`Factory`, `HTMLHelper`, `Text`, `LayoutHelper`, `Route`) и текущий стиль проекта.
- Экранируйте вывод (`$this->escape()`, `htmlspecialchars()`, `HTMLHelper::cleanImageURL()`, приведения типов). POST/AJAX/forms должны учитывать Joomla CSRF token и права доступа.
- Пользовательские строки - только через `Text::_()` и `.ini`; frontend строки кладите в `tpl_itheme.ini` обеих локалей.
- Bootstrap-разметка должна быть Bootstrap 5.3 (`data-bs-*`), без Bootstrap 4 атрибутов.
- Поддерживайте accessibility: корректные `button/a`, `aria-label`, `visually-hidden`, focus states, возврат фокуса в modal/offcanvas.
- Не редактируйте `node_modules`; vendored Bootstrap меняйте только как осознанный апгрейд.

## Проверка

Минимум перед сдачей: `pnpm build`, `pnpm test`, `pnpm zip`. Если Node.js/pnpm недоступны, явно укажите, что проверки не запускались.

Функциональная проверка после установки zip через Joomla installer: главная, категория, карточка товара, корзина, checkout, поиск, логин, 403/404, offline page.

## Ограничения

Это шаблон Joomla, а не полный сайт: корневые PHP-файлы не запускаются полноценно вне Joomla application context. Автотестов пока нет, `pnpm test` - заглушка.
