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
 */
$attributes = [];
$attributes['class'] = 'nav-link';

if ($item->anchor_title) {
    $attributes['class'] .= ' ' . $item->anchor_css;
}

if ($item->anchor_css) {
    $attributes['class'] = $item->anchor_css;
}

if ($item->anchor_rel) {
    $attributes['rel'] = $item->anchor_rel;
}

$linktype = $item->title;

// В данном шаблоне вывод иконок и изображений не предусмотрено, закомментируем.
/*if ($item->menu_icon) {
    // The link is an icon
    if ($itemParams->get('menu_text', 1)) {
        // If the link text is to be displayed, the icon is added with aria-hidden
        $linktype = '<span class="p-2 pt-0 ' . $item->menu_icon . '" aria-hidden="true"></span>' . $item->title;
    } else {
        // If the icon itself is the link, it needs a visually hidden text
        $linktype = '<span class="p-2 pt-0 ' . $item->menu_icon . '" aria-hidden="true"></span><span class="visually-hidden">' . $item->title . '</span>';
    }
} elseif ($item->menu_image) {
    // The link is an image, maybe with an own class
    $image_attributes = [];

    if ($item->menu_image_css) {
        $image_attributes['class'] = $item->menu_image_css;
    }

    $linktype = HTMLHelper::_('image', $item->menu_image, '', $image_attributes);

    $linktype .= '<span class="image-title' . ($itemParams->get('menu_text', 1) ? '' : ' visually-hidden') . '">' . $item->title . '</span>';
}*/

if ($item->browserNav == 1) {
    $attributes['target'] = '_blank';
    $attributes['rel'] = 'noopener noreferrer';
    if ($item->anchor_rel == 'nofollow') {
        $attributes['rel'] .= ' nofollow';
    }
} elseif ($item->browserNav == 2) {
    $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
    $attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

echo HTMLHelper::_('link', OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)), $linktype, $attributes);
