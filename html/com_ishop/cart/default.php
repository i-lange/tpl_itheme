<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\ImageHelper;
use Ilange\Component\Ishop\Site\Helper\PriceHelper;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_ishop.cart');
$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));

if ($this->cart->total > 0) {
    $dataLayerItems = [];
    foreach ($this->cart->products as $i => $product) {
        if (empty($product->count)) {
            continue;
        }

        $dataLayerItems[] = [
            'item_id'       => $product->id,
            'item_name'     => $this->escape($product->fullname),
            'discount'      => $product->discount_size,
            'index'         => $i,
            'item_brand'    => $product->manufacturer_title,
            'item_category' => $product->category_title,
            'price'         => ($product->sale_price > 0) ? $product->sale_price : $product->price,
            'quantity'      => $product->count,
        ];
    }
    $jsonLayerItems = json_encode($dataLayerItems, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $dataLayer = 'const dataLayerItems = ' . $jsonLayerItems . ';';
    $wa->addInlineScript($dataLayer);
    $dataLayer = 'gtag("event","view_cart",{currency:"' . $currency . '",value:"' . $this->cart->summary . '",items:dataLayerItems});';
    $wa->addInlineScript($dataLayer);
}
$count = count($this->cart->products);
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <h1><?php echo $this->escape($this->params->get('page_heading')); ?> <span class="fw-normal text-body-tertiary"><?php echo $count; ?> шт.</span></h1>
<?php endif; ?>
<?php if ($count > 0) : ?>
    <form class="module-cart-grid"
          id="cart-submit"
          action="<?php echo Route::_(RouteHelper::getCheckoutRoute()); ?>"
          method="post"
          name="cart-submit"
          data-cart-empty-text="<?php echo $this->escape(Text::_('COM_ISHOP_CART_NULL')); ?>">
        <div>
            <?php foreach ($this->cart->products as $key_id => $product) : ?>
                <div class="module-cart-item<?php echo ($product->available) ? '' : ' not_available'; ?>" data-product-incart-id="<?php echo $product->id; ?>">
                    <?php echo LayoutHelper::render('itheme.product.thumb', ['item' => $product, 'class' => 'cart-item__image']); ?>
                    <?php if ($product->available) : ?>
                        <div class="cart-item__checkbox">
                            <label class="visually-hidden" for="check_input_<?php echo $key_id; ?>"></label>
                            <input class="form-check-input"
                                   id="check_input_<?php echo $key_id; ?>"
                                   type="checkbox"
                                   name="products[]"
                                   checked="checked"
                                   value="<?php echo $product->id; ?>">
                        </div>
                    <?php endif; ?>
                    <div class="cart-item__info">
                        <div class="position-relative">
                            <?php if ($product->available) : ?>
                                <?php echo LayoutHelper::render('itheme.product.prices', ['item' => $product]); ?>
                                <?php if (!empty($product->delivery)) : ?>
                                    <div><span class="text-body-emphasis">Доставим</span> <?php echo $product->delivery; ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo Text::_('COM_ISHOP_PRODUCT_NOT_AVAILABLE'); ?>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($product->fullname); ?></h3>
                            <?php if ($product->introtext !== '') : ?>
                                <p class="mt-2 d-n d-md-block text-body-emphasis"><?php echo $product->introtext; ?></p>
                            <?php endif; ?>
                            <a class="stretched-link" href="<?php echo Route::_(RouteHelper::getProductRoute((int)$product->id, (int)$product->catid)); ?>">
                                <span class="visually-hidden"><?php echo $this->escape($product->fullname); ?></span>
                            </a>
                        </div>
                        <div class="cart-item_controls">
                            <div class="cart-item_quantity">
                                <?php if ($product->available) : ?>
                                    <label class="visually-hidden" for="quantity_input_<?php echo $key_id; ?>"></label>
                                    <button class="btn btn-primary"
                                            title="<?php echo Text::_('COM_ISHOP_CART_MINUS'); ?>"
                                            type="button"
                                            data-cart-button="minus"><span class="visually-hidden"><?php echo Text::_('COM_ISHOP_CART_MINUS'); ?></span>-</button>
                                    <input class="quantity_input"
                                           id="quantity_input_<?php echo $key_id; ?>"
                                           type="number"
                                           name="quantity[<?php echo $product->id; ?>]"
                                           value="<?php echo $product->incart_count; ?>"
                                           min="0"
                                           readonly
                                           data-quantity>
                                    <button class="btn btn-primary"
                                            title="<?php echo Text::_('COM_ISHOP_CART_PLUS'); ?>"
                                            type="button"
                                            data-cart-button="plus"><span class="visually-hidden"><?php echo Text::_('COM_ISHOP_CART_PLUS'); ?></span>+</button>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-light"
                                    title="<?php echo Text::_('COM_ISHOP_CART_REMOVE'); ?>"
                                    type="button"
                                    data-cart-button="delete">
                                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-trash']); ?>
                                <span class="d-none d-lg-inline"><?php echo Text::_('COM_ISHOP_CART_REMOVE'); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <div class="module-cart-checkout">
                <h3><?php echo Text::_('COM_ISHOP_CART_YOUR_ORDER'); ?></h3>
                <div class="info-line">
                    <span><?php echo Text::_('COM_ISHOP_CART_SELECTED_TOTAL'); ?>:</span>
                    <?php echo LayoutHelper::render('itheme.product.price', [
                            'price' => $this->cart->total, 'class' => 'cart-price', 'attribs' => 'data-cart-total', 'minus' => true]); ?>
                </div>
                <div class="info-line">
                    <span><?php echo Text::_('COM_ISHOP_CART_SELECTED_SALE'); ?>:</span>
                    <?php echo LayoutHelper::render('itheme.product.price', [
                            'price' => $this->cart->total, 'class' => 'cart-price', 'attribs' => 'data-cart-total-discount', 'minus' => true]); ?>
                </div>
                <div class="info-line h5">
                    <span><?php echo Text::_('COM_ISHOP_CART_SUM'); ?>:</span>
                    <?php echo LayoutHelper::render('itheme.product.price', [
                            'price' => $this->cart->total, 'class' => 'cart-price', 'attribs' => 'data-cart-summary', 'minus' => true]); ?>
                </div>
                <?php if ($this->cart->total > 0) : ?>
                    <button class="btn btn-primary btn-lg"
                            title="<?php echo Text::_('COM_ISHOP_CART_CHECKOUT'); ?>"
                            type="submit">
                        <span><?php echo Text::_('COM_ISHOP_CART_CHECKOUT'); ?></span><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-right']); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
<?php else : ?>
    <div class="module-cart-empty">
        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-cart', 'class' => 'mega primary']); ?>
        <p><?php echo Text::_('COM_ISHOP_CART_NULL'); ?></p>
    </div>
<?php endif; ?>
