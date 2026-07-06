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

/** @var $displayData */

$params  = $displayData->params;
$images  = json_decode($displayData->images);

if (empty($images->image_fulltext)) {
    return;
}

$imgclass   = empty($images->float_fulltext) ? $params->get('float_fulltext') : $images->float_fulltext;
$layoutAttr = [
    'class' => 'object-fit-contain',
    'src'      => $images->image_fulltext,
    'alt'      => empty($images->image_fulltext_alt) && empty($images->image_fulltext_alt_empty) ? false : $images->image_fulltext_alt,
    'sizes' => '(max-width: 439px) 100, 50vw',
];
?>
<div class="mb-4">
    <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
    <?php echo LayoutHelper::render('itheme.image', $layoutAttr); ?>
    </div>
    <?php if (isset($images->image_fulltext_caption) && $images->image_fulltext_caption !== '') : ?>
        <div><?php echo $this->escape($images->image_fulltext_caption); ?></div>
    <?php endif; ?>
</div>
