<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Ilange\Component\Ishop\Site\Helper\ImageHelper;

$user = Factory::getApplication()->getIdentity();
$groups = $user->getAuthorisedViewLevels();
?>
<?php if ($this->children[$this->category->id] > 0) : ?>
<div class="catalog__grid">
<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
    <?php if (in_array($child->access, $groups)) : ?>
        <a href="<?php echo Route::_(RouteHelper::getCategoryRoute($child->id, $child->language)); ?>"
               class="catalog__card"
               aria-label="<?php echo $this->escape($child->title); ?>">
            <?php if ($this->params->get('show_description_image') && $child->getParams()->get('image')) : ?>
                <div class="catalog__card-img">
                    <?php echo LayoutHelper::render('itheme.image_category', $child); ?>
                </div>
            <?php endif; ?>
            <span class="catalog__card-title"><?php echo $this->escape($child->title); ?></span>
            <?php if ($this->params->get('show_subcat_desc_cat') == 1) : ?>
                <?php if ($child->description) : ?>
                    <div class="catalog__card-desc">
                        <?php echo HTMLHelper::_('content.prepare', $child->description, '', 'com_ishop.categories'); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </a>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php endif; ?>
