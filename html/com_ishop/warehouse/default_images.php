<?php
/**
 * @package    com_ishop
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/com_ishop
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.carousel');
$wa->useScript('tpl.gallery');

// Сделаем ссылку на изображения товара
$images = $this->item->images;
// Дополнительные фото товара
$more = isset($images->image_more) ? (array) $images->image_more : [];

$slides = [];

if (!empty($images->image_main)) {
    $alt = $images->image_main_alt ?: $this->item->title;
    $slides[] = [
        'type'     => 'image',
        'src'      => $images->image_main,
        'thumbSrc' => $images->image_main,
        'alt'      => $alt,
    ];
}

foreach ($more as $image) {
    if (empty($image->image_item)) {
        continue;
    }

    $alt = $image->image_item_alt ?: $this->item->title;
    $slides[] = [
        'type'     => 'image',
        'src'      => $image->image_item,
        'thumbSrc' => $image->image_item,
        'alt'      => $alt,
    ];
}

if (empty($slides)) {
    return;
}

echo LayoutHelper::render('itheme.gallery', [
    'id'         => 'warehouseSlider',
    'slides'     => $slides,
    'class'      => 'warhouse-full__gallery',
    'ratioClass' => 'ratio ratio-3x4',
    'imageSizes' => '(max-width: 439px) 100vw, 50vw',
    'thumbSizes' => '(max-width: 767px) 4.5rem, 5rem',
]);
