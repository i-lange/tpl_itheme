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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Ilange\Component\Ishop\Site\Helper\ImageHelper;

/** @var Ilange\Component\Ishop\Site\View\Categories\HtmlView $this */

if ($this->maxLevelcat != 0 && count($this->items[$this->parent->id]) > 0) : ?>
<?php foreach ($this->items[$this->parent->id] as $id => $item) : ?>
    <?php if (count($item->getChildren()) > 0 && $this->maxLevelcat > 1) : ?>
        <?php
            $this->items[$item->id] = $item->getChildren();
            $this->parent = $item;
            $this->maxLevelcat--;
            echo $this->loadTemplate('items');
            $this->parent = $item->getParent();
            $this->maxLevelcat++;
        ?>
    <?php else: ?>
            <a href="<?php echo Route::_(RouteHelper::getCategoryRoute($item->id, $item->language)); ?>"
               class="catalog__card"
               aria-label="<?php echo $this->escape($item->title); ?>">
                <?php if ($this->params->get('show_description_image') && $item->getParams()->get('image')) : ?>
                    <div class="catalog__card-img">
                        <?php echo LayoutHelper::render('itheme.image_category', $item); ?>
                    </div>
                <?php endif; ?>
                <span class="catalog__card-title"><?php echo $this->escape($item->title); ?></span>
                <?php if ($this->params->get('show_subcat_desc_cat') == 1) : ?>
                    <?php if ($item->description) : ?>
                        <div class="catalog__card-desc">
                            <?php echo HTMLHelper::_('content.prepare', $item->description, '', 'com_ishop.categories'); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </a>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>