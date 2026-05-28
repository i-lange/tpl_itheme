<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.carousel');
?>
<?php if (!empty($slides = $this->params->get('frontpage_slider_list', []))) : ?>
<div id="frontpageSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5500">
    <div class="carousel-indicators">
    <?php for ($i = 0; $i < count($slides); $i++) : ?>
        <button type="button"
                data-bs-target="#frontpageSlider"
                data-bs-slide-to="<?php echo $i; ?>"<?php echo ($i === 0) ? 'class="active" aria-current="true"' : ''; ?>></button>
    <?php endfor; ?>
    </div>
    <div class="carousel-inner">
        <?php $i = 0; ?>
        <?php foreach ($slides as $slide) : ?>
            <div class="carousel-item<?php echo ($i === 0) ? ' active' : ''; ?>"><?php $i++; ?>
                <div class="promo__slide">
                    <?php if (!empty($slide['image'])) : ?>
                        <?php echo LayoutHelper::render('itheme.image_slide', $slide); ?>
                    <?php endif; ?>
                    <?php if (!empty($slide['title']) && !empty($slide['desc'])) : ?>
                        <div class="promo__slide-body">
                            <div class="container">
                                <div class="promo__slide-content">
                                    <?php if (!empty($slide['badge'])) : ?>
                                    <span class="promo__slide-badge"><?php echo $slide['badge']; ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['title'])) : ?>
                                    <h1 class="promo__slide-title"><?php echo $slide['title']; ?></h1>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['desc'])) : ?>
                                    <p class="promo__slide-text"><?php echo $slide['desc']; ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['btn']) && !empty($slide['url'])) : ?>
                                        <a href="<?php echo $slide['url']; ?>"
                                           class="btn btn-primary"><?php echo $slide['btn']; ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($slide['url']) || empty($slide['btn'])) : ?>
                        <a class="promo__slide-cover" href="<?php echo $slide['url']; ?>" title="<?php echo $slide['title']; ?>">
                            <span class="visually-hidden"><?php echo $slide['title']; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#frontpageSlider" data-bs-slide="prev" aria-label="<?php echo Text::_('TPL_ITHEME_PREV_SLIDE'); ?>">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_PREV_SLIDE'); ?></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#frontpageSlider" data-bs-slide="next" aria-label="<?php echo Text::_('TPL_ITHEME_PREV_SLIDE'); ?>">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_PREV_SLIDE'); ?></span>
    </button>
</div>
<?php unset($slides); ?>
<?php endif; ?>