<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$app   = Factory::getApplication();
$input = $app->getInput();
$wa    = $this->getWebAssetManager();

// Метаданные страницы
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
// <meta http-equiv="X-UA-Compatible" content="IE=edge">
$this->setMetaData('X-UA-Compatible', 'IE=edge', 'http-equiv');
// <meta name="format-detection" content="telephone=no">
$this->setMetaData('format-detection', 'telephone=no');
// <meta name="format-detection" content="address=no">
$this->setMetaData('format-detection', 'address=no');
// <meta name="theme-color" content="#c40e38"/>
$this->setMetaData('theme-color', '#c40e38');

// Иконки приложения
$this->addHeadLink(HTMLHelper::_('image', 'favicon.ico', '', [], true, 1), 'alternate icon', 'rel', ['type' => 'image/vnd.microsoft.icon']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.svg', '', [], true, 1), 'icon', 'rel', ['type' => 'image/svg+xml']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon.svg', '', [], true, 1), 'mask-icon', 'rel', ['color' => '#000']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon-16x16.png', '', [], true, 1), 'shortcut icon', 'rel', ['sizes' => '16x16']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon-32x32.png', '', [], true, 1), 'shortcut icon', 'rel', ['sizes' => '32x32']);
$this->addHeadLink(HTMLHelper::_('image', 'favicon-96x96.png', '', [], true, 1), 'shortcut icon', 'rel', ['sizes' => '96x96']);

// Манифест приложения
$this->addHeadLink('/manifest.webmanifest', 'manifest');

// Параметры текущей страницы
$option   = $input->getCmd('option', '');
$view     = $input->getCmd('view', '');
$layout   = $input->getCmd('layout', '');
$task     = $input->getCmd('task', '');

// Класс текущей страницы (включая установленный в пункте меню)
$menu     = $app->getMenu()->getActive();
$page_class = ($menu !== null) ? $menu->getParams()->get('pageclass_sfx', '') : '';
$page_class .= (($page_class === '') ? '' : $page_class . ' ') .
        $option .
        ' vw_'  . $view .
        ($layout ? ' lt_' . $layout : '') .
        ($task ? ' tk_' . $task : '') .
        ($this->direction == 'rtl' ? ' rtl' : '');

// Нужно ли фиксировать главный main контейнер по ширине
$needContainer = true;
if ($option === 'com_ishop') {
    if (in_array($view, ['frontpage', 'category', 'product'])) {
        $needContainer = false;
    }
}

// Если нужен прилипающий header
if ($this->params->get('stickyHeader', false)) {
    $wa->useScript('tpl.header-sticky');
}

// Логотип сайта
$sitename = Text::_('TPL_ITHEME_SITENAME');
if ($this->params->get('logoFile')) {
	$logo = HTMLHelper::_('image', Uri::root() . htmlspecialchars($this->params->get('logoFile'), ENT_QUOTES), $sitename, ['loading' => 'eager', 'decoding' => 'async'], false, 0);
} elseif ($this->params->get('siteTitle')) {
	$logo = '<span title="' . $sitename . '">' . htmlspecialchars($this->params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
} else {
	$logo = HTMLHelper::_('image', 'logo.svg', $sitename, ['class' => 'logo d-inline-block', 'loading' => 'eager', 'decoding' => 'async'], true, 0);
}

// Подключаем основные таблицы стилей шаблона:
$wa->useStyle('tpl.itheme');
// Подключаем основные таблицы скрипты шаблона:
$wa->useScript('tpl.itheme');
// Рендеринг модулей не будет работать должным образом при неполной инициализации приложения
$renderModules = $app->getIdentity() && $app->getLanguage();

// Имя файла с иконками из настроек шаблона
$iconsFile = HTMLHelper::cleanImageURL($this->params->get('iconsFile'))->url;