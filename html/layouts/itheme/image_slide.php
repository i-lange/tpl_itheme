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

if (empty($image)) {
    return;
}

$layoutAttr = [
    'src' => $image,
    'alt' => empty($title) ? false : $title,
    'class' => 'w-100',
    'sizes' => '(max-width: 439px) 100vw, 50vw',
];

echo LayoutHelper::render('itheme.image', $layoutAttr);
