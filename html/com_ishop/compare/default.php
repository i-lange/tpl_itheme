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
$wa->useScript('bootstrap.dropdown');
$wa->useScript('tpl.drag-scroller');

$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
$round = (int) $this->params->get('roundPrice', 0);
$catId = $this->category_id;
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
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        title="<?php echo Text::_('COM_ISHOP_MSG_SELECT_CATEGORY'); ?>">
                    <span class="dropdown-text"><?php echo $this->compare[$catId]->title; ?></span>
                    <small class="badge rounded-pill text-bg-primary ms-1"><?php echo $this->compare[$catId]->count; ?></small></button>
                <ul class="dropdown-menu">
                    <?php foreach ($this->compare as $category) : ?>
                        <?php
                        $attribs = ($catId === $category->id)
                                ? ' class="dropdown-item active"'
                                : ' class="dropdown-item" onclick="document.getElementById(\'compare-category-id\').value=\'' . $category->id .
                                '\';document.getElementById(\'compare-submit\').submit();"';
                        ?>
                        <li>
                            <a <?php echo $attribs; ?> href="#">
                                <span><?php echo $category->title; ?></span> <small class="badge rounded-pill <?php echo($catId === $category->id) ? 'text-bg-light' : 'text-bg-primary'; ?> ms-1"><?php echo $category->count; ?></small>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </form>
        <div class="module-compare-scroll" data-drag-scroller>
        <div class="module-compare-scroll-inner">
            <div class="module-compare-products module-compare mt-3">
            <?php foreach ($this->compare[$catId]->products as $product) : ?>
                <?php echo LayoutHelper::render('itheme.product.small', ['item' => $product, 'params' => $this->params]) ?>
            <?php endforeach; ?>
            </div>
            <?php $products_list = array_keys($this->compare[$catId]->products); ?>
            <?php foreach ($this->compare[$catId]->groups as $group) : ?>
                <?php if (!empty($group->fields)) : ?>
                    <h3 class="compare-group-title"><?php echo $group->title; ?></h3><br>
                    <?php foreach ($group->fields as $field) : ?>
                        <?php if (!empty($field->products)) : ?>
                            <div class="compare-field-title"><?php echo $field->title; ?></div>
                            <div class="module-compare compare-value-list<?php echo ($field->ismixed) ? ' mixed' : ''; ?>">
                                <?php foreach ($products_list as $id) : ?>
                                    <div>
                                        <?php if (isset($field->products[$id])) : ?>
                                            <?php $value = $field->products[$id]; ?>
                                            <?php if ($field->type === 2) : ?>
                                                <?php echo ($value->value === 'y') ? Text::_('COM_ISHOP_YES') : Text::_('COM_ISHOP_NO'); ?>
                                            <?php else: ?>
                                                <?php if ($field->type === 0) $value->value = FormatHelper::renderFloat($value->value); ?>
                                                <?php echo $value->value, ' ', ($field->unit !== '') ? ' ' . $field->unit : ''; ?>
                                            <?php endif; ?>
                                            <?php echo ($value->hint !== '') ? ' (' . $value->hint . ')' : ''; ?>
                                        <?php else : ?>-<?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        </div>
    <?php else : ?>
        <div class="module-cart-empty">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-compare', 'class' => 'mega primary']); ?>
            <p><?php echo Text::_('COM_ISHOP_COMPARE_NULL'); ?></p>
        </div>
    <?php endif; ?>
</div>