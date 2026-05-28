<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Filter\OutputFilter;

/**
 * Список доступных переменных
 * @var stdClass $module
 * @var Joomla\CMS\Application\CMSApplicationInterface $app
 * @var Joomla\Input\Input $input
 * @var Joomla\Registry\Registry $params
 * @var stdClass $template
 * @var Joomla\CMS\WebAsset\WebAssetManager $wa
 * @var string $class_sfx
 * @var array $list
 * @var int $default_id
 * @var int $active_id
 * @var array $path
 * @var object $item
 * @var Joomla\Registry\Registry $itemParams
 * @var string $iconsFile
 */
$attributes = [];
$attributes['class'] = 'nav-link';

if ($item->anchor_title) {
    $attributes['title'] = $item->anchor_title;
}

/*if ($item->anchor_css) {
    $attributes['class'] .= ' ' . $item->anchor_css;
}*/

if ($item->anchor_rel) {
    $attributes['rel'] = $item->anchor_rel;
}

$linktype = $item->title;

// Вывод изображений или иконок
if ($item->menu_icon) {
    // Иконка в ссылке
    if ($itemParams->get('menu_text', 1)) {
        // Если текст нужно отображать
        $linktype =
            LayoutHelper::render('itheme.icon', ['icon' => $item->menu_icon]) .
            '<span>' . $item->title . '</span>';

    } else {
        // Если текст не нужно отображать
        $linktype =
            LayoutHelper::render('itheme.icon', ['icon' => $item->menu_icon]) .
            '<span class="visually-hidden">' . $item->title. '</span>';
    }
} elseif ($item->menu_image) {
    // Изображение в ссылке
    $image_attributes = [];
    if ($item->menu_image_css) {
        $image_attributes['class'] = $item->menu_image_css;
    }
    $linktype = HTMLHelper::_('image', $item->menu_image, '', $image_attributes);
    $linktype .= '<span class="' . ($itemParams->get('menu_text', 1) ? '' : ' visually-hidden') . '">' . $item->title . '</span>';
}

if ($item->browserNav == 1) {
    $attributes['target'] = '_blank';

    if ($item->type === 'url') {
        $attributes['rel'] = 'noopener noreferrer';
        if ($item->anchor_rel == 'nofollow') {
            $attributes['rel'] .= ' nofollow';
        }
    }
} elseif ($item->browserNav == 2) {
    $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
    $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
