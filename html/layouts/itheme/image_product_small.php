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
use Joomla\CMS\Layout\LayoutHelper;

/** @var object $displayData Объект товара */
$src = $displayData->images->image_small;

if (empty($src)) {
    $src = Factory::getApplication()->getTemplate(true)->params->get('placeholderFile');
}

$layoutAttr = [
    'src' => $src,
    'alt' => (!empty($displayData->images->image_small_alt)) ?: $displayData->fullname,
    'class' => 'image',
    'sizes' => '(max-width: 439px) 100vw, 50vw',
];

echo LayoutHelper::render('itheme.image', $layoutAttr);