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
use Joomla\Utilities\ArrayHelper;

/**
 * @var   array  $displayData  Массив со всеми заданными атрибутами для элемента изображения.
 *                             Например: src, class, alt, width, height, loading, decoding, style, data-*
 *                             Примечание: по умолчанию экранируются только атрибуты alt и src.
 */

$img = HTMLHelper::cleanImageURL($displayData['src']);

$displayData['src'] = $this->escape($img->url);
$displayData['decoding'] = 'async';
$displayData['itemprop'] = 'image';

if (isset($displayData['class'])) {
    $displayData['class'] .= ' is-placeholder';
} else {
    $displayData['class'] = 'is-placeholder';
}

if (isset($displayData['alt'])) {
    if ($displayData['alt'] === false) {
        unset($displayData['alt']);
    } else {
        $displayData['alt'] = $this->escape($displayData['alt']);
    }
}

if ($img->attributes['width'] > 0 && $img->attributes['height'] > 0) {
    $displayData['width']  = $img->attributes['width'];
    $displayData['height'] = $img->attributes['height'];
}

if (isset($displayData['data-srcset']) && isset($displayData['sizes'])) {
    $displayData['srcset'] = 'media/templates/site/itheme/images/phd.svg';
    $displayData['data-toggle-class'] = 'is-placeholder';
} elseif (isset($displayData['sizes'])) {
    $displayData['srcset'] = 'media/templates/site/itheme/images/phd.svg';
    $displayData['data-toggle-class'] = 'is-placeholder';
    $displayData['data-srcset'] = '/' . ltrim($img->url, '/') . ' ' . $img->attributes['width'] . 'w';
}

echo '<img ' . ArrayHelper::toString($displayData) . '>';
