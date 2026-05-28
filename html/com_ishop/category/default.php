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
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

/** @var Ilange\Component\Ishop\Site\View\Category\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.dropdown');

$ordering = $this->escape($this->state->get('list.ordering', 'a.price'));
$direction = $this->escape($this->state->get('list.direction', 'DESC'));
$fullOrdering = $ordering . ' ' . $direction;
$text = ltrim($ordering, 'a.') . '_' . $direction;
$sort = ($direction === 'DESC') ? 'down' : 'up';
$orderingList = $this->params->get('category_ordering', []);
$showFilter = (!empty(ModuleHelper::getModules('filter'))) && !empty($this->filter_object) && !$this->filter_object->empty;
?>
<div class="container">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php endif; ?>
    <?php if ($this->params->get('show_category_title')) : ?>
        <h2><?php echo $this->category->title; ?> <span class="category-products-count">(<?php echo $this->pagination->total; ?>)</span></h2>
    <?php endif; ?>
    <?php if ($this->maxLevel != 0 && $this->get('children')) : ?>
        <?php echo $this->loadTemplate('children'); ?>
    <?php endif; ?>
    <div class="mb-3 d-flex justify-content-between">
        <div class="dropdown">
            <button class="btn btn-light btn-sm border dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-sort-' . $sort]); ?>
                <?php echo Text::_('COM_ISHOP_ORDER_' . $text); ?></button>
            <ul class="dropdown-menu">
                <?php foreach ($orderingList as $item) : ?>
                    <?php
                        $full = $item->field . ' ' . $item->dir;
                        $attribs = ($full == $fullOrdering)
                                ? ' class="dropdown-item active"'
                                : ' class="dropdown-item" onclick="document.getElementById(\'filter_ordering\').value=\'' . $item->field .
                                '\';document.getElementById(\'filter_direction\').value=\'' . $item->dir .
                                '\';document.getElementById(\'category-ordering\').submit();"';
                    ?>
                    <li><a <?php echo $attribs; ?> href="#"><?php echo Text::_('COM_ISHOP_ORDER_' . ltrim($item->field, 'a.')  . '_' . $item->dir); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <form id="category-ordering"
              class="d-none"
              action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
              method="post"
              name="category-ordering">
            <input type="hidden" name="filter_order" id="filter_ordering" value="<?php echo $ordering; ?>">
            <input type="hidden" name="filter_order_Dir" id="filter_direction" value="<?php echo $direction; ?>">
        </form>
        <?php if ($showFilter) : ?>
            <button class="btn btn-light btn-sm border"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#moduleFilter"
                    aria-controls="moduleFilter"
                    aria-label="<?php echo Text::_('TPL_ITHEME_FILTER_ANCHOR'); ?>"
                    title="<?php echo Text::_('COM_ISHOP_MSG_OPEN_FILTER'); ?>">
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-funnel']); ?>
                <span><?php echo Text::_('TPL_ITHEME_FILTER_ANCHOR'); ?></span>
                <?php if ($this->filter_object->active_count > 0) : ?>
                <small><?php echo $this->filter_object->active_count; ?></small>
                <?php endif; ?>
            </button>
        <?php endif; ?>
    </div>
    <div class="products__grid">
    <?php echo $this->loadTemplate('items'); ?>
    </div>
    <?php if (!empty($this->items) && $this->pagination->pagesTotal > 1) : ?>
        <?php echo $this->pagination->getPagesLinks(); ?>
    <?php endif; ?>
</div>
<?php if ($this->params->get('show_description', 1) && $this->category->description) : ?>
<div class="bg-light py-5">
    <div class="container">
        <?php echo HTMLHelper::_('content.prepare', $this->category->description, '', 'com_ishop.category'); ?>
    </div>
</div>
<?php endif; ?>
