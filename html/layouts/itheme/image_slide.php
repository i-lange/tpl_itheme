<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);

if (empty($bg_image)) {
    return;
}

$layoutAttr = [
    'src' => $bg_image,
    'alt' => false,
    'class' => 'promo__slide-bg-image w-100 h-100 object-fit-cover',
    'sizes' => '100vw',
];

echo LayoutHelper::render('itheme.image', $layoutAttr);
