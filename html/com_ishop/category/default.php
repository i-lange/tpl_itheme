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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

/** @var Ilange\Component\Ishop\Site\View\Category\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_ishop');
$wa->useScript('bootstrap.dropdown')
    ->useScript('com_ishop.products-loader');

$ordering = $this->escape($this->state->get('list.ordering', 'a.price'));
$direction = $this->escape($this->state->get('list.direction', 'DESC'));
$fullOrdering = $ordering . ' ' . $direction;
$text = ltrim($ordering, 'a.') . '_' . $direction;
$sort = ($direction === 'DESC') ? 'down' : 'up';
$orderingList = $this->params->get('category_ordering', []);
$hasSidebarFilter = static function (string $position): bool {
    foreach (ModuleHelper::getModules($position) as $module) {
        if (($module->module ?? '') === 'mod_ishop_filter') {
            return true;
        }
    }

    return false;
};
$showFilter = !empty($this->filter_object)
    && !$this->filter_object->empty
    && ($hasSidebarFilter('sidebar-left') || $hasSidebarFilter('sidebar-right'));
$categoryTitle = !empty($this->filter_seo_page->heading) ? $this->filter_seo_page->heading : $this->category->title;
$limit = max(1, (int) $this->state->get('list.limit', $app->get('list_limit', 20)));
$limitstart = (int) $this->state->get('list.start', 0);
$total = (int) $this->pagination->total;
$nextLimitstart = $limitstart + $limit;
$hasMore = !empty($this->items) && $nextLimitstart < $total;
$loaderStateId = 'ishop-category-loader-state-' . (int) $this->category->id;
$loaderState = [
    'id'              => (int) $this->category->id,
    'category_id'     => (int) $this->category->id,
    'Itemid'          => $app->getInput()->getInt('Itemid', 0),
    'filter_order'    => $ordering,
    'filter_order_Dir'=> $direction,
    'filter_tag'      => (int) $this->state->get('filter.tag', 0),
    'filter-search'   => (string) $this->state->get('list.filter', ''),
    'filter_route'    => (int) $this->state->get('filter.route', 0),
    'min_price'       => (int) $this->state->get('filter.min_price', 0),
    'max_price'       => (int) $this->state->get('filter.max_price', 0),
    'good_price'      => (int) $this->state->get('filter.good_price', 0),
    'min_width'       => (int) $this->state->get('filter.min_width', 0),
    'max_width'       => (int) $this->state->get('filter.max_width', 0),
    'min_height'      => (int) $this->state->get('filter.min_height', 0),
    'max_height'      => (int) $this->state->get('filter.max_height', 0),
    'min_depth'       => (int) $this->state->get('filter.min_depth', 0),
    'max_depth'       => (int) $this->state->get('filter.max_depth', 0),
    'min_weight'      => (int) $this->state->get('filter.min_weight', 0),
    'max_weight'      => (int) $this->state->get('filter.max_weight', 0),
    'manufacturers'   => (array) $this->state->get('filter.manufacturers', []),
    'warehouses'      => (array) $this->state->get('filter.warehouses', []),
    'ishop_fields'    => (array) $this->state->get('filter.ishop_fields', []),
    'manufacturer_id' => (int) $this->state->get('filter.manufacturer_id', 0),
];

if ($this->state->get('filter.warehouse_id', false) !== false) {
    $loaderState['warehouse_id'] = (int) $this->state->get('filter.warehouse_id', 0);
}
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<?php if ($this->params->get('show_category_title')) : ?>
    <h2><?php echo $categoryTitle; ?> <span class="category-products-count"><?php echo $total; ?>&nbsp;<?php echo Text::_('COM_ISHOP_PSC'); ?></span></h2>
<?php endif; ?>
<?php if ($this->maxLevel != 0 && $this->get('children')) : ?>
    <?php echo $this->loadTemplate('children'); ?>
<?php endif; ?>
<?php if ($total === 0 ) : ?>
<div class="d-lg-flex flex-column min-vh-50 align-items-center justify-content-center">
    <h3 class="h1 text-body-tertiary"><?php echo Text::_('COM_ISHOP_CATEGORY_NO_ITEMS'); ?></h3>
    <p class=""><?php echo Text::_('COM_ISHOP_CATEGORY_FILTER_RESET'); ?></p>
</div>
<?php else: ?>
    <div class="mb-3 d-flex justify-content-between">
        <div class="dropdown">
            <button class="btn btn-link btn-tools dropdown-toggle"
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
            <button class="btn btn-link btn-tools d-lg-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#moduleFilter"
                    aria-controls="moduleFilter"
                    aria-label="<?php echo Text::_('TPL_ITHEME_FILTER_ANCHOR'); ?>"
                    title="<?php echo Text::_('COM_ISHOP_MSG_OPEN_FILTER'); ?>">
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-funnel']); ?>
                <span><?php echo Text::_('TPL_ITHEME_FILTER_ANCHOR'); ?></span>
                <?php if ($this->filter_object->active_count > 0) : ?>
                    <small class="badge text-bg-primary rounded-pill"><?php echo $this->filter_object->active_count; ?></small>
                <?php endif; ?>
            </button>
        <?php endif; ?>
    </div>
    <div class="products__grid"
         data-ishop-products
         data-ishop-context="category"
         data-ishop-endpoint="<?php echo Route::_('index.php?option=com_ishop&task=products.load&format=json', false); ?>"
         data-ishop-state="<?php echo $loaderStateId; ?>"
         data-ishop-token="<?php echo Session::getFormToken(); ?>"
         data-ishop-limit="<?php echo $limit; ?>"
         data-ishop-total="<?php echo $total; ?>"
         data-ishop-next-limitstart="<?php echo $nextLimitstart; ?>"
         data-ishop-has-more="<?php echo $hasMore ? '1' : '0'; ?>"
         data-ishop-currency="<?php echo strtoupper($this->params->get('defaultCurrency', 'BYN')); ?>">
        <?php echo $this->loadTemplate('items'); ?>
    </div>
    <script type="application/json" id="<?php echo $loaderStateId; ?>"><?php echo json_encode($loaderState, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <?php
    $filterSeoDescription = !empty($this->filter_seo_page->description) ? $this->filter_seo_page->description : '';
    $categoryDescription = $filterSeoDescription ?: $this->category->description;
    $showCategoryDescription = $filterSeoDescription || $this->params->get('show_description', 1);
    ?>
    <?php if ($showCategoryDescription && $categoryDescription) : ?>
        <div class="py-5">
            <?php echo HTMLHelper::_('content.prepare', $categoryDescription, '', 'com_ishop.category'); ?>
        </div>
    <?php endif; ?>
<?php endif;