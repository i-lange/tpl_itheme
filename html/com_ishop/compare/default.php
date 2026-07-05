<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\FormatHelper;
use Ilange\Component\Ishop\Site\Helper\ImageHelper;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_ishop');
$wa->useScript('tpl.drag-scroller');
$wa->useScript('tpl.compare-sticky');

if ($this->params->get('use_js', true) && $this->params->get('use_compare', false)) {
    $wa->useScript('com_ishop.addtocompare');
}

$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
$round = (int) $this->params->get('roundPrice', 0);
$catId = (int) $this->category_id;
?>
<div class="container pb-5">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1><?php echo $this->params->get('page_heading'); ?></h1>
    <?php endif; ?>
    <?php if (!empty($this->compare)) : ?>
        <form id="compare-submit"
              action="<?php echo Route::_(RouteHelper::getCompareRoute()); ?>"
              method="post"
              name="compare-submit">
            <?php echo HTMLHelper::_('form.token'); ?>
            <input id="compare-category-id" type="hidden" name="category_id" value="<?php echo $catId; ?>">
            <div class="scroll-items-list mb-3 gap-1 gap-md-2" data-drag-scroller data-drag-scroller-interactive>
                <?php foreach ($this->compare as $category) : ?>
                    <?php
                    $categoryId = (int) $category->id;
                    $attribs = ($catId === $categoryId)
                            ? ' class="btn btn-tag active"'
                            : ' class="btn btn-tag"';
                    ?>
                    <button<?php echo $attribs; ?>
                            type="button"
                            data-ishop-compare-category="<?php echo $categoryId; ?>">
                        <span class="btn-title"><?php echo $category->title; ?> (<?php echo $category->count; ?>)</span>
                        <span class="btn btn-close"
                              aria-hidden="true"
                              data-ishop-compare-remove-category="<?php echo $categoryId; ?>"></span>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="btn-toolbar" role="toolbar" aria-label="<?php echo Text::_('COM_ISHOP_COMPARE_VIEW_MODE'); ?>">
                <div class="btn-group"
                     role="group"
                     aria-label="<?php echo Text::_('COM_ISHOP_COMPARE_VIEW_MODE'); ?>"
                     data-ishop-compare-mode>
                    <button type="button"
                            class="btn btn-primary"
                            data-ishop-compare-mode-button="all"
                            aria-pressed="true">
                        <?php echo Text::_('COM_ISHOP_COMPARE_VIEW_ALL'); ?>
                    </button>
                    <button type="button"
                            class="btn btn-light"
                            data-ishop-compare-mode-button="diff"
                            aria-pressed="false">
                        <?php echo Text::_('COM_ISHOP_COMPARE_VIEW_DIFF'); ?>
                    </button>
                </div>
            </div>
        </form>
        <div class="module-compare-scroll" data-drag-scroller data-compare-sticky>
        <div class="module-compare-scroll-inner">
            <div class="module-compare-products mt-3" data-compare-sticky-panel>
            <div class="module-compare-products-inner module-compare" data-compare-sticky-track>
            <?php foreach ($this->compare[$catId]->products as $product) : ?>
                <?php $compareRating = (int)($product->compare_rating ?? 0); ?>
                <?php $compareWins = (int)($product->compare_wins ?? 0); ?>
                <div class="compare-product compare-rating-<?php echo $compareRating; ?>"
                     data-product-id="<?php echo (int)$product->id; ?>"
                     data-compare-wins="<?php echo $compareWins; ?>"
                     data-compare-rating="<?php echo $compareRating; ?>">
                    <?php if ($compareRating === 1) : ?><span class="badge text-bg-primary"><?php echo Text::_('COM_ISHOP_COMPARE_GOOD_CHOICE'); ?></span><?php endif; ?>
                    <?php if ($compareRating === 2) : ?><span class="badge text-bg-warning"><?php echo Text::_('COM_ISHOP_COMPARE_BEST_CHOICE'); ?></span><?php endif; ?>
                    <?php echo LayoutHelper::render('itheme.product.small-inline', ['item' => $product, 'params' => $this->params, 'compareRemove' => true]) ?>
                </div>
            <?php endforeach; ?>
            </div>
            </div>
            <?php $products_list = array_keys($this->compare[$catId]->products); ?>
            <?php foreach ($this->compare[$catId]->groups as $group) : ?>
                <?php if (!empty($group->fields)) : ?>
                    <div class="compare-group" data-ishop-compare-group>
                    <h3 class="compare-group-title"><?php echo $group->title; ?></h3>
                    <?php foreach ($group->fields as $field) : ?>
                        <?php if (!empty($field->products)) : ?>
                            <div class="compare-row" data-ishop-compare-row data-ishop-compare-mixed="<?php echo $field->ismixed ? '1' : '0'; ?>">
                            <div class="compare-field-title <?php echo ($field->ismixed) ? ' mixed' : ''; ?>"><?php echo $field->title; ?></div>
                            <div class="module-compare compare-value-list">
                                <?php foreach ($products_list as $id) : ?>
                                    <?php $isBest = isset($field->products[$id]) && !empty($field->products[$id]->is_best); ?>
                                    <div<?php echo $isBest ? ' class="is-best"' : ''; ?>>
                                        <?php if (isset($field->products[$id])) : ?>
                                            <?php $value = $field->products[$id]; ?>
                                            <?php if ($field->type === 2) : ?>
                                                <?php echo ($value->value === 'y')
                                                        ? LayoutHelper::render('itheme.icon', ['icon' => 'i-check', 'class' => 'text-success me-2']) . Text::_('COM_ISHOP_YES')
                                                        : LayoutHelper::render('itheme.icon', ['icon' => 'i-close', 'class' => 'text-danger me-2']) .Text::_('COM_ISHOP_NO'); ?>
                                            <?php else: ?>
                                                <?php echo $isBest ? LayoutHelper::render('itheme.icon', ['icon' => 'i-best', 'class' => 'text-warning me-1']) : ''; ?>
                                                <?php if ($field->type === 0) $value->value = FormatHelper::renderFloat($value->value); ?>
                                                <?php echo $value->value, ' ', ($field->unit !== '') ? ' ' . $field->unit : ''; ?>
                                            <?php endif; ?>
                                            <?php echo ($value->hint !== '') ? ' (' . $value->hint . ')' : ''; ?>
                                        <?php else : ?>-<?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        </div>
        <form id="compare-remove-submit"
              action="<?php echo Route::_(RouteHelper::getCompareRoute()); ?>"
              method="post"
              hidden>
            <input type="hidden" name="task" value="">
            <input type="hidden" name="category_id" value="">
            <input type="hidden" name="product_id" value="">
            <?php echo HTMLHelper::_('form.token'); ?>
        </form>
    <?php else : ?>
        <div class="d-flex flex-column min-vh-50 align-items-center justify-content-center">
            <h3 class="h1 text-body-tertiary"><?php echo Text::_('COM_ISHOP_COMPARE_NULL'); ?></h3>
            <?php echo LayoutHelper::render('itheme.product.tocatalog', ['class' => 'btn-lg btn-primary']); ?>
        </div>
    <?php endif; ?>
</div>
