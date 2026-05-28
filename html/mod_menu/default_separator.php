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
$title      = $item->anchor_title ? ' title="' . $item->anchor_title . '"' : '';
$anchor_css = $item->anchor_css ?: '';
$linktype   = $item->title;

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
    // The link is an image, maybe with its own class
    $image_attributes = [];

    if ($item->menu_image_css) {
        $image_attributes['class'] = $item->menu_image_css;
    }

    $linktype = HTMLHelper::_('image', $item->menu_image, '', $image_attributes);

    if ($itemParams->get('menu_text', 1)) {
        $linktype .= '<span class="image-title">' . $item->title . '</span>';
    }
}*/
?>
<span class="nav-separator <?php echo $anchor_css; ?>"<?php echo $title; ?>><?php echo $linktype; ?></span>
