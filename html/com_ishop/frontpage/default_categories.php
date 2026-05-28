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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Ilange\Component\Ishop\Site\Helper\ImageHelper;

/** @var Ilange\Component\Ishop\Site\View\Frontpage\HtmlView $this */
?>
<h2><?php echo Text::_('TPL_ITHEME_CATALOG'); ?></h2>
<div class="catalog__grid">
<?php if (count($this->categories) > 0) : ?>
    <?php foreach ($this->categories as $id => $item) : ?>
        <a href="<?php echo Route::_(RouteHelper::getCategoryRoute($item->id, $item->language)); ?>"
           class="catalog__card"
           aria-label="<?php echo $this->escape($item->title); ?>">
            <?php if ($this->params->get('show_description_image') && $item->getParams()->get('image')) : ?>
                <div class="catalog__card-img">
                    <?php echo LayoutHelper::render('itheme.image_category', $item); ?>
                </div>
            <?php endif; ?>
            <span class="catalog__card-title"><?php echo $this->escape($item->title); ?></span>
        </a>
    <?php endforeach; ?>
<?php endif; ?>
</div>