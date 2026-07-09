<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;

extract($displayData);

/** @var object $item Объект товара */
/** @var object $params Параметры магазина */

// Если не передали параметры, заберем из компонента
if (empty($params)) {
    $params = ComponentHelper::getParams('com_ishop');
}

$compareRemove = !empty($compareRemove);

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_ishop');

if ($params->get('use_js', true) && $params->get('use_cart', false)) {
    $wa->useScript('com_ishop.addtocart');
}

if ($params->get('use_js', true) && $params->get('use_compare', false)) {
    $wa->useScript('com_ishop.addtocompare');
}

if ($params->get('use_js', true) && $params->get('use_wishlist', false)) {
    $wa->useScript('com_ishop.addtowishlist');
}
?>
<article class="product-small-inline" data-product-id="<?php echo $item->id; ?>">
    <div class="product-small-inline__body">
        <div class="product-small-inline__image ratio ratio-3x4 <?php echo ($item->discount_size > 0) ? 'sale' : '' ;?>">
            <?php echo LayoutHelper::render('itheme.image_product_small', $item); ?>
        </div>
        <div class="product-small-inline__info">
            <?php echo LayoutHelper::render('itheme.product.prices', ['item' => $item]); ?>
            <?php echo LayoutHelper::render('itheme.product.title', ['item' => $item]); ?>
            <a class="product-small-inline__link"
               href="<?php echo Route::_(RouteHelper::getProductRoute((int)$item->id, (int)$item->catid)); ?>"
               data-isiteanalytics-select-item
               data-isiteanalytics-product-id="<?php echo (int) $item->id; ?>">
                <span class="visually-hidden"><?php echo $this->escape($item->fullname); ?></span>
            </a>
        </div>
        <?php if ($compareRemove) : ?>
            <button class="btn btn-close"
                    type="button"
                    aria-label="<?php echo Text::_('COM_ISHOP_COMPARE_REMOVE_PRODUCT'); ?>"
                    data-ishop-compare-remove-product="<?php echo (int) $item->id; ?>"></button>
        <?php endif; ?>
    </div>
<?php if (!$item->available) : ?>
    <div class="btn btn-sm btn-secondary disabled"><?php echo Text::_('COM_ISHOP_PRODUCT_NOT_AVAILABLE'); ?></div>
<?php endif; ?>
    <div class="product-small-inline__footer">
        <?php echo LayoutHelper::render('itheme.product.buy1click', ['item' => $item, 'class' => 'btn-sm btn-primary mr-1']); ?>
        <?php echo LayoutHelper::render('itheme.product.addtocart', ['item' => $item, 'class' => 'btn-sm btn-light']); ?>
    </div>
</article>
