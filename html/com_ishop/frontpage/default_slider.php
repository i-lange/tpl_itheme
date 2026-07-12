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

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.carousel');
?>
<?php if (!empty($slides = $this->params->get('frontpage_slider_list', []))) : ?>
<div id="frontpageSlider" class="promo-carousel carousel slide" data-bs-ride="carousel" data-bs-interval="5500">
    <div class="carousel-indicators container">
    <?php for ($i = 0; $i < count($slides); $i++) : ?>
        <button type="button"
                data-bs-target="#frontpageSlider"
                data-bs-slide-to="<?php echo $i; ?>"<?php echo ($i === 0) ? ' class="active" aria-current="true"' : ''; ?>></button>
    <?php endfor; ?>
    </div>
    <div class="carousel-inner">
        <?php $i = 0; ?>
        <?php foreach ($slides as $slide) : ?>
            <?php
            $slideTitle = (string) ($slide['title'] ?? '');
            $hasButton  = !empty($slide['btn']) && !empty($slide['url']);
            ?>
            <div class="carousel-item<?php echo ($i === 0) ? ' active' : ''; ?>"><?php $i++; ?>
                <div class="promo__slide">
                    <?php if (!empty($slide['bg_image'])) : ?>
                        <div class="promo__slide-bg">
                            <?php echo LayoutHelper::render('itheme.image_slide', $slide); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($slide['title']) || !empty($slide['desc']) || !empty($slide['image'])) : ?>
                        <div class="promo__slide-body">
                            <div class="container h-100">
                                <div class="promo__slide-row row align-items-center h-100">
                                    <?php if (!empty($slide['image'])) : ?>
                                        <div class="promo__slide-media col-12 col-md-6 order-md-2">
                                            <?php echo LayoutHelper::render('itheme.image', [
                                                    'class' => 'promo__slide-image img-fluid',
                                                    'src' => $slide['image'],
                                                    'alt' => empty($slideTitle) ? false : $slideTitle,
                                                    'sizes' => '(max-width: 767px) 100vw, 50vw',
                                            ]); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="promo__slide-copy col-12<?php echo !empty($slide['image']) ? ' col-md-6' : ' col-md-7'; ?> order-md-1">
                                        <div class="promo__slide-content">
                                            <?php if (!empty($slide['badge'])) : ?>
                                            <span class="promo__slide-badge"><?php echo $slide['badge']; ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['title']) || !empty($slide['sub_title'])) : ?>
                                            <h1 class="promo__slide-title"><?php echo $slideTitle; ?> <?php echo (!empty($slide['sub_title']))
                                                        ? '<br class="d-none d-lg-inline"><span class="text-white">' . $slide['sub_title'] . '</span>' : ''; ?></h1>
                                            <?php endif; ?>
                                            <?php if (!empty($slide['desc'])) : ?>
                                            <p class="promo__slide-text"><?php echo $slide['desc']; ?></p>
                                            <?php endif; ?>
                                            <?php if ($hasButton) : ?>
                                                <a href="<?php echo $slide['url']; ?>"
                                                   class="promo__slide-btn btn btn-lg btn-primary"><?php echo $slide['btn']; ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($slide['url']) && !$hasButton) : ?>
                        <a class="promo__slide-cover" href="<?php echo $slide['url']; ?>" title="<?php echo $slideTitle; ?>">
                            <span class="visually-hidden"><?php echo $slideTitle; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php unset($slides); ?>
<?php endif; ?>
