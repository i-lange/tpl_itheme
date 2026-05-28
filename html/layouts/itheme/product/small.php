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

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa ->useScript('tpl.addtocart')
    ->useScript('tpl.addtocompare')
    ->useScript('tpl.addtowishlist');

$currency = strtoupper($params->get('defaultCurrency', 'BYN'));
$round = (int) $params->get('roundPrice', 0);
$product_price = ($item->sale_price > 0) ? $item->sale_price : $item->price;
?>
<article class="product-small" data-product-id="<?php echo $item->id; ?>">
    <div class="product-small__body">
        <div class="product-small__image <?php echo ($item->discount_size > 0) ? 'sale' : '' ;?>">
            <?php echo LayoutHelper::render('itheme.image_product_small', $item); ?>
            <?php if (!empty($item->attribs)) : ?>
                <div class="product__attribs">
                    <?php foreach ($item->attribs as $text => $value) : ?>
                        <?php if (!empty($value)) : ?>
                            <div class="product__attrib <?php echo $text; ?>">
                                <?php echo Text::_('COM_ISHOP_' . $text); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="product__labels">
                <?php if ($item->discount_size > 0) : ?>
                    <div class="product__label label_size">-<?php echo $item->discount_size; ?>%</div>
                    <div class="product__label label_price"><?php echo Text::_('COM_ISHOP_PRODUCT_GOOD_PRICE'); ?></div>
                <?php endif; ?>
                <?php foreach ($item->parts as $part) : ?>
                    <div class="product__label label_<?php echo $part->cats_label; ?>">
                        <?php if (!empty($part->icon)) : ?>
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => $part->icon]); ?>
                        <?php endif; ?>
                        <?php if ($part->cats_label_param > 0) : ?>
                            <?php switch ($part->cats_label_param) {
                                case 1:
                                    echo Text::sprintf(
                                        'COM_ISHOP_FIELD_CATS_LABEL_' . $part->cats_label,
                                        $part->min_payment);
                                    break;
                                case 2:
                                    echo Text::sprintf(
                                        'COM_ISHOP_FIELD_CATS_LABEL_' . $part->cats_label,
                                        $part->min_rate . '%');
                                    break;
                                case 3:
                                    echo Text::sprintf(
                                        'COM_ISHOP_FIELD_CATS_LABEL_' . $part->cats_label,
                                        $part->max_period);
                                    break;
                            }
                            ?>
                        <?php else: ?>
                            <?php echo Text::_('COM_ISHOP_FIELD_CATS_LABEL_0'); ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($item->price) : ?>
            <div class="product-small__prices">
                <div class="product-small__price <?php echo ($item->discount_size > 0) ? 'sale' : '';?>">
                    <?php if ($item->discount_size > 0) : ?>
                        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-sale']); ?>
                    <?php endif; ?>
                    <?php echo round($product_price, $round); ?><span class="currency"><?php echo $currency; ?></span>
                </div>
                <?php if ($item->old_price > 0) : ?>
                    <div class="product-small__old-price">
                        <del><?php echo round($item->old_price, $round); ?></del> <span class="currency"><?php echo $currency; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <h3 class="product-small__title">
            <span class="brand"><?php echo $this->escape($item->manufacturer_title); ?></span><span class="model"> / <?php echo $this->escape($item->title); ?></span>
        </h3>
        <div class="product-small__prefix"><?php echo $this->escape($item->prefix); ?></div>
        <?php if (!$item->available) : ?>
            <div class="product-small__not_available"><?php echo Text::_('COM_ISHOP_PRODUCT_NOT_AVAILABLE'); ?></div>
        <?php endif; ?>
        <a class="product-small__link" href="<?php echo Route::_(RouteHelper::getProductRoute((int)$item->id, (int)$item->catid)); ?>">
            <span class="visually-hidden"><?php echo $this->escape($item->fullname); ?></span>
        </a>
        <?php echo LayoutHelper::render('itheme.product.tools', ['item' => $item]); ?>
    </div>
    <?php echo LayoutHelper::render('itheme.product.addtocart', ['item' => $item]); ?>
    <?php echo LayoutHelper::render('itheme.product.buy1click', ['item' => $item, 'class' => 'mt-1']); ?>
</article>