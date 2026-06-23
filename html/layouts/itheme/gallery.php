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

$id         = preg_replace('/[^A-Za-z0-9_-]/', '', $displayData['id'] ?? '');
$slides     = array_values(array_filter((array) ($displayData['slides'] ?? [])));
$class      = trim('itheme-gallery ' . ($displayData['class'] ?? ''));
$ratioClass = trim($displayData['ratioClass'] ?? 'ratio ratio-3x4');
$imageSizes = $displayData['imageSizes'] ?? '(max-width: 439px) 100vw, 50vw';
$thumbSizes = $displayData['thumbSizes'] ?? '5rem';
$toolsHtml  = $displayData['toolsHtml'] ?? '';
$hasThumbs  = count($slides) > 1;

if (empty($slides)) {
    return;
}

if (empty($id)) {
    $id = 'ithemeGallery' . substr(md5(uniqid('', true)), 0, 8);
} elseif (!preg_match('/^[A-Za-z]/', $id)) {
    $id = 'ithemeGallery' . $id;
}

$renderImage = static function (string $src, string|bool $alt, string $class, string $sizes): string {
    return LayoutHelper::render('itheme.image', [
        'src'   => $src,
        'alt'   => $alt,
        'class' => $class,
        'sizes' => $sizes,
    ]);
};
?>
<div id="<?php echo $this->escape($id); ?>"
     class="<?php echo $this->escape($class); ?><?php echo $hasThumbs ? ' carousel slide' : ''; ?>"
     <?php echo $hasThumbs ? 'data-itheme-gallery' : ''; ?>>
    <div class="itheme-gallery__stage">
        <div class="<?php echo $hasThumbs ? 'carousel-inner' : 'itheme-gallery__single'; ?>">
            <?php foreach ($slides as $index => $slide) : ?>
                <?php
                $type = $slide['type'] ?? 'image';
                $alt  = $slide['alt'] ?? '';
                ?>
                <div class="<?php echo $hasThumbs ? 'carousel-item' . ($index === 0 ? ' active' : '') : 'itheme-gallery__single-item'; ?>">
                    <div class="<?php echo $this->escape($ratioClass); ?>">
                        <?php if ($type === 'video' && !empty($slide['video'])) : ?>
                            <?php echo LayoutHelper::render('itheme.video', ['video' => $slide['video']]); ?>
                        <?php elseif (!empty($slide['src'])) : ?>
                            <?php echo $renderImage($slide['src'], $alt, 'object-fit-contain', $imageSizes); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php echo $toolsHtml; ?>
    </div>

    <?php if ($hasThumbs) : ?>
        <div class="itheme-gallery__nav">
            <button class="itheme-gallery__control itheme-gallery__control--prev d-none d-md-inline-flex"
                    type="button"
                    data-bs-target="#<?php echo $this->escape($id); ?>"
                    data-bs-slide="prev"
                    aria-label="<?php echo Text::_('TPL_ITHEME_PREV'); ?>">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_PREV'); ?></span>
            </button>

            <div class="itheme-gallery__thumbs carousel-indicators" data-itheme-gallery-thumbs>
                <?php foreach ($slides as $index => $slide) : ?>
                    <?php
                    $type     = $slide['type'] ?? 'image';
                    $thumbSrc = $slide['thumbSrc'] ?? $slide['src'] ?? '';
                    $alt      = $slide['alt'] ?? '';
                    $label    = $type === 'video'
                        ? Text::sprintf('TPL_ITHEME_SHOW_VIDEO', $index + 1)
                        : Text::sprintf('TPL_ITHEME_SHOW_SLIDE', $index + 1);

                    ?>
                    <button class="itheme-gallery__thumb<?php echo $index === 0 ? ' active' : ''; ?>"
                            type="button"
                            data-bs-target="#<?php echo $this->escape($id); ?>"
                            data-bs-slide-to="<?php echo (int) $index; ?>"
                            <?php echo $index === 0 ? 'aria-current="true"' : ''; ?>
                            aria-label="<?php echo $this->escape($label); ?>">
                        <span class="itheme-gallery__thumb-image ratio ratio-1x1">
                            <?php if (!empty($thumbSrc)) : ?>
                                <?php echo $renderImage($thumbSrc, false, 'object-fit-cover', $thumbSizes); ?>
                            <?php else : ?>
                                <span class="itheme-gallery__thumb-placeholder"></span>
                            <?php endif; ?>
                            <?php if ($type === 'video') : ?>
                                <span class="itheme-gallery__play" aria-hidden="true"></span>
                            <?php endif; ?>
                        </span>
                    </button>
                <?php endforeach; ?>
            </div>

            <button class="itheme-gallery__control itheme-gallery__control--next d-none d-md-inline-flex"
                    type="button"
                    data-bs-target="#<?php echo $this->escape($id); ?>"
                    data-bs-slide="next"
                    aria-label="<?php echo Text::_('TPL_ITHEME_NEXT'); ?>">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_NEXT'); ?></span>
            </button>
        </div>
    <?php endif; ?>
</div>
