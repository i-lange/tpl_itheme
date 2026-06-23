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

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.carousel');
$wa->useScript('tpl.gallery');

// Сделаем ссылку на изображения товара
$images = $this->item->images;
// Дополнительные фото товара
$more = isset($images->image_more) ? (array) $images->image_more : [];
// Прикрепленные видео ролики
$videos = isset($this->item->videos) ? (array) $this->item->videos : [];

$slides = [];
$videoThumb = '';

if (!empty($images->image_main)) {
    $alt = $images->image_main_alt ?: $this->item->fullname;
    $slides[] = [
        'type'     => 'image',
        'src'      => $images->image_main,
        'thumbSrc' => $images->image_main,
        'alt'      => $alt,
    ];
    $videoThumb = $images->image_main;
}

foreach ($more as $image) {
    if (empty($image->image_item)) {
        continue;
    }

    $alt = $image->image_item_alt ?: $this->item->fullname;
    $slides[] = [
        'type'     => 'image',
        'src'      => $image->image_item,
        'thumbSrc' => $image->image_item,
        'alt'      => $alt,
    ];

    if (empty($videoThumb)) {
        $videoThumb = $image->image_item;
    }
}

foreach ($videos as $video) {
    if (empty($video->video_id) || empty($video->video_source)) {
        continue;
    }

    $slides[] = [
        'type'     => 'video',
        'video'    => $video,
        'thumbSrc' => $videoThumb,
        'alt'      => $this->item->fullname,
    ];
}

if (empty($slides)) {
    return;
}

$toolsHtml = '';

if ($this->params->get('use_wishlist', false)) {
    ob_start();
    ?>
    <div class="product_tools">
        <button class="btn btn-lg<?php echo ($this->item->inwishlist) ? ' active' : ''; ?>"
                title="<?php echo Text::_('TPL_ITHEME_BTN_WISHLIST'); ?>"
                data-towishlist="<?php echo (int) $this->item->id; ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-like']); ?>
            <span class="visually-hidden"><?php echo Text::_('TPL_ITHEME_BTN_WISHLIST'); ?></span>
        </button>
    </div>
    <?php
    $toolsHtml = ob_get_clean();
}

echo LayoutHelper::render('itheme.gallery', [
    'id'         => 'productFullSlider',
    'slides'     => $slides,
    'class'      => 'product-full__gallery',
    'ratioClass' => 'ratio ratio-3x4',
    'imageSizes' => '(max-width: 439px) 100vw, 50vw',
    'thumbSizes' => '(max-width: 767px) 4.5rem, 5rem',
    'toolsHtml'  => $toolsHtml,
]);
