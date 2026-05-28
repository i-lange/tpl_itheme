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
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var $displayData */

$params  = $displayData->params;
$images  = json_decode($displayData->images);

if (empty($images->image_intro)) {
    return;
}

$imgclass   = empty($images->float_intro) ? $params->get('float_intro') : $images->float_intro;
$layoutAttr = [
    'class' => 'card-img-top ' . $imgclass,
    'src' => $images->image_intro,
    'alt' => empty($images->image_intro_alt) && empty($images->image_intro_alt_empty) ? false : $images->image_intro_alt,
    'sizes' => '(max-width: 439px) 100, 50vw',
];
?>
<?php echo LayoutHelper::render('itheme.image', $layoutAttr); ?>
<?php if (isset($images->image_intro_caption) && $images->image_intro_caption !== '') : ?>
<div><?php echo $this->escape($images->image_intro_caption); ?></div>
<?php endif; ?>