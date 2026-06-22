<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\ImageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.carousel');

$i = 0;

// Сделаем ссылку на изображения товара
$images = $this->item->images;
// Дополнительные фото товара
$more = isset($images->image_more) ? (array) $images->image_more : [];
// Прикрепленные видео ролики
$videos = isset($this->item->videos) ? (array) $this->item->videos : [];
?>
<?php if ((!empty($more) && count($more)) || (!empty($videos) && count($videos))) : ?>
    <div id="productFullSlider" class="carousel slide product-full__carousel">
        <div class="carousel-inner">
            <?php if (!empty($images->image_main)) : ?>
                <?php $i++; ?>
                <div class="carousel-item active">
                    <?php $alt = $images->image_main_alt ?: $this->item->fullname; ?>
                    <div class="ratio ratio-3x4">
                        <?php echo LayoutHelper::render('itheme.image', [
                                'src' => $images->image_main,
                                'alt' => $alt,
                                'class' => 'object-fit-contain',
                                'sizes' => '(max-width: 439px) 100vw, 50vw',
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php foreach ($more as $image) : ?>
                <?php if (!empty($image->image_item)) : ?>
                    <?php $alt = $image->image_item_alt ?: $this->item->fullname; ?>
                    <div class="carousel-item<?php echo ($i === 0) ? ' active' : ''; ?>">
                        <?php $i++; ?>
                        <div class="ratio ratio-3x4">
                            <?php echo LayoutHelper::render('itheme.image', [
                                    'src' => $image->image_item,
                                    'alt' => $alt,
                                    'class' => 'object-fit-contain',
                                    'sizes' => '(max-width: 439px) 100vw, 50vw',
                            ]); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($videos as $video) : ?>
                <div class="carousel-item<?php echo ($i === 0) ? ' active' : ''; ?>">
                    <?php $i++; ?>
                    <div class="ratio ratio-3x4">
                        <?php echo LayoutHelper::render('itheme.video', ['video' => $video]); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#productFullSlider" data-bs-slide="prev" aria-label="<?php echo Text::_('TPL_ITHEME_PREV'); ?>">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_PREV'); ?></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productFullSlider" data-bs-slide="next" aria-label="<?php echo Text::_('TPL_ITHEME_NEXT'); ?>">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_NEXT'); ?></span>
        </button>
        <?php if ($this->params->get('use_wishlist', false)) : ?>
        <div class="product_tools">
            <button class="btn btn-lg<?php echo ($this->item->inwishlist) ? ' active' : ''; ?>"
                    title="<?php echo Text::_('TPL_ITHEME_BTN_WISHLIST'); ?>"
                    data-towishlist="<?php echo $this->item->id; ?>">
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-like']); ?>
                <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_BTN_WISHLIST'); ?></span>
            </button>
        </div>
        <?php endif; ?>
    </div>
<?php elseif (!empty($images->image_main)) : ?>
    <div class="product-full__image">
        <?php $alt = $images->image_main_alt ?: $this->item->fullname; ?>
        <div class="ratio ratio-3x4">
            <?php echo LayoutHelper::render('itheme.image', [
                    'src' => $images->image_main,
                    'alt' => $alt,
                    'class' => 'object-fit-contain',
                    'sizes' => '(max-width: 439px) 100vw, 50vw',
            ]); ?>
        </div>
    </div>
<?php endif; ?>
