<?php
/**
 * @package    com_ishop
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/com_ishop
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.carousel');

// Сделаем ссылку на изображения товара
$images = $this->item->images;
// Дополнительные фото товара
$more = isset($images->image_more) ? (array) $images->image_more : [];
$i = 0;
?>
<?php if (!empty($more) && count($more) > 1) : ?>
    <div id="warehouseSlider" class="carousel slide warhouse-full__slider">
        <div class="carousel-inner">
            <?php foreach ($more as $image) : ?>
                <?php if (!empty($image->image_item)) : ?>
                    <?php $alt = $image->image_item_alt ?: $this->item->title; ?>
                    <div class="carousel-item<?php echo ($i === 0) ? ' active' : ''; ?>">
                        <?php $i++; ?>
                        <?php echo LayoutHelper::render('itheme.image', [
                                'src' => $image->image_item,
                                'alt' => $alt,
                                'sizes' => '(max-width: 439px) 100vw, 50vw',
                        ]); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#warehouseSlider" data-bs-slide="prev" aria-label="<?php echo Text::_('TPL_ITHEME_PREV'); ?>">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_PREV'); ?></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#warehouseSlider" data-bs-slide="next" aria-label="<?php echo Text::_('TPL_ITHEME_NEXT'); ?>">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_NEXT'); ?></span>
        </button>
    </div>
<?php else: ?>
    <?php if (!empty($images->image_main)) : ?>
        <div class="warhouse-full__image">
            <?php $alt = $images->image_main_alt ?: $this->item->title; ?>
            <?php echo LayoutHelper::render('itheme.image', [
                    'src' => $images->image_main,
                    'alt' => $alt,
                    'sizes' => '(max-width: 439px) 100vw, 50vw',
            ]); ?>
        </div>
    <?php endif; ?>
<?php endif; ?>