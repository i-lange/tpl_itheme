<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\PriceHelper;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_ishop.cart');
$wa->useScript('bootstrap.alert');
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
    <form class="module-cart"
          id="cart-submit"
          action="<?php echo Route::_(RouteHelper::getCheckoutRoute()); ?>"
          method="post"
          name="cart-submit"
          data-cart-empty-text="<?php echo $this->escape(Text::_('COM_ISHOP_CART_NULL')); ?>">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-md-7 col-lg-8 col-xl-9">
                <div class="cart-removed-alert alert alert-dismissible d-none align-items-start justify-content-between gap-3 mb-3"
                     role="alert"
                     data-cart-removed-alert
                     data-cart-removed-text="<?php echo $this->escape(Text::_('COM_ISHOP_CART_REMOVED_NOTICE')); ?>">
                    <div>
                        <div data-cart-removed-message></div>
                        <button class="btn btn-link p-0 fw-semibold text-primary text-decoration-none"
                                type="button"
                                data-cart-restore>
                            <?php echo Text::_('COM_ISHOP_CART_UNDO'); ?>
                        </button>
                    </div>
                    <button type="button"
                            class="btn-close position-static flex-shrink-0 p-0"
                            data-cart-alert-close
                            aria-label="<?php echo Text::_('TPL_ITHEME_CLOSE'); ?>"></button>
                </div>

                <div class="cart-toolbar d-flex align-items-center justify-content-between gap-3 py-2 mb-2">
                    <div class="form-check d-flex align-items-center gap-2 mb-0">
                        <input class="form-check-input mt-0"
                               id="cart_select_all"
                               type="checkbox"
                               data-cart-select-all
                               checked>
                        <label class="form-check-label" for="cart_select_all"><?php echo Text::_('COM_ISHOP_CART_SELECT_ALL'); ?></label>
                    </div>
                    <button class="btn btn-link p-0 text-primary text-decoration-none"
                            type="button"
                            data-cart-remove-selected>
                        <?php echo Text::_('COM_ISHOP_CART_REMOVE_SELECTED'); ?>
                    </button>
                </div>

                <div class="cart-items">
                    <?php foreach ($this->cart->products as $key_id => $product) : ?>
                        <?php
                        $productId = (int) $product->id;
                        $productRoute = Route::_(RouteHelper::getProductRoute($productId, (int) $product->catid));
                        ?>
                        <div class="module-cart-item<?php echo ($product->available) ? '' : ' not_available'; ?>" data-product-incart-id="<?php echo $productId; ?>">
                            <div class="cart-item__checkbox form-check mb-0">
                                <input class="form-check-input"
                                       id="check_input_<?php echo $key_id; ?>"
                                       type="checkbox"
                                    <?php echo ($product->available) ? 'name="products[]" checked="checked" value="' . $productId . '" data-cart-item-checkbox' : 'disabled'; ?>>
                                <label class="visually-hidden" for="check_input_<?php echo $key_id; ?>"><?php echo $this->escape($product->fullname); ?></label>
                            </div>

                            <div class="cart-item__thumb">
                                <?php echo LayoutHelper::render('itheme.product.thumb', ['item' => $product, 'class' => 'cart-item__image']); ?>
                            </div>

                            <div class="cart-item__main">
                                <div class="cart-item__body">
                                    <?php if ($product->available) : ?>
                                        <?php echo LayoutHelper::render('itheme.product.prices', ['item' => $product, 'class' => 'cart-item__prices']); ?>
                                    <?php else : ?>
                                        <div class="cart-item__unavailable fw-semibold text-body-secondary"><?php echo Text::_('COM_ISHOP_PRODUCT_NOT_AVAILABLE'); ?></div>
                                    <?php endif; ?>

                                    <h3 class="cart-item__title mb-0">
                                        <a class="text-body text-decoration-none" href="<?php echo $productRoute; ?>">
                                            <?php echo htmlspecialchars($product->fullname, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </h3>

                                    <?php if ($product->available && !empty($product->delivery)) : ?>
                                        <div class="mt-1"><span class="text-body-emphasis">Доставим</span> <?php echo $product->delivery; ?></div>
                                    <?php endif; ?>

                                    <?php if ($product->introtext !== '') : ?>
                                        <p class="cart-item__intro mb-0 text-body-secondary"><?php echo $product->introtext; ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="cart-item_controls d-flex align-items-center justify-content-between gap-3">
                                    <div class="cart-item_quantity">
                                        <?php if ($product->available) : ?>
                                            <label class="visually-hidden" for="quantity_input_<?php echo $key_id; ?>"><?php echo Text::_('COM_ISHOP_CART_SELECTED_TOTAL'); ?></label>
                                            <button class="btn"
                                                    title="<?php echo Text::_('COM_ISHOP_CART_MINUS'); ?>"
                                                    type="button"
                                                    data-cart-button="minus"><span class="visually-hidden"><?php echo Text::_('COM_ISHOP_CART_MINUS'); ?></span>-</button>
                                            <input class="quantity_input"
                                                   id="quantity_input_<?php echo $key_id; ?>"
                                                   type="number"
                                                   name="quantity[<?php echo $productId; ?>]"
                                                   value="<?php echo (int) $product->incart_count; ?>"
                                                   min="0"
                                                   readonly
                                                   data-quantity>
                                            <button class="btn cart-item_quantity-plus"
                                                    title="<?php echo Text::_('COM_ISHOP_CART_PLUS'); ?>"
                                                    type="button"
                                                    data-cart-button="plus"><span class="visually-hidden"><?php echo Text::_('COM_ISHOP_CART_PLUS'); ?></span>+</button>
                                        <?php endif; ?>
                                    </div>
                                    <button class="btn btn-light cart-item__remove"
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
            </div>
            <div class="col-12 col-md-5 col-lg-4 col-xl-3 sticky-sm-top">
                <aside class="module-cart-checkout">
                    <div class="cart-promocode mb-3">
                        <label class="visually-hidden" for="cart_promocode"><?php echo Text::_('COM_ISHOP_CART_PROMOCODE'); ?></label>
                        <input class="form-control"
                               id="cart_promocode"
                               type="text"
                               name="promocode"
                               value="<?php echo $this->escape($this->cart->promocode ?? ''); ?>"
                               placeholder="<?php echo Text::_('COM_ISHOP_CART_PROMOCODE'); ?>"
                               autocomplete="off"
                               data-cart-promocode>
                        <button class="btn btn-primary"
                                type="button"
                                aria-label="<?php echo Text::_('COM_ISHOP_CART_APPLY_PROMOCODE'); ?>"
                                data-cart-apply-promocode>
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-right']); ?>
                        </button>
                        <div class="form-text <?php echo empty($this->cart->promocode_message) ? 'd-none' : (($this->cart->promocode_valid ?? false) ? 'text-success' : 'text-danger'); ?>"
                             data-cart-promocode-message>
                            <?php echo $this->escape($this->cart->promocode_message ?? ''); ?>
                        </div>
                    </div>

                    <div class="cart-summary-lines">
                        <div class="info-line">
                            <span><?php echo Text::_('COM_ISHOP_CART_SELECTED_TOTAL'); ?>:</span>
                            <?php echo LayoutHelper::render('itheme.product.price', [
                                    'price' => $this->cart->total, 'class' => 'cart-price', 'attribs' => 'data-cart-total', 'minus' => true]); ?>
                        </div>
                        <div class="info-line">
                            <span><?php echo Text::_('COM_ISHOP_CART_ACTION_DISCOUNT'); ?></span>
                            <?php echo LayoutHelper::render('itheme.product.price', [
                                    'price' => $this->cart->total_discount, 'class' => 'cart-price', 'attribs' => 'data-cart-total-discount', 'minus' => true]); ?>
                        </div>
                        <div class="info-line">
                            <span><?php echo Text::_('COM_ISHOP_CART_PROMO_DISCOUNT'); ?></span>
                            <?php echo LayoutHelper::render('itheme.product.price', [
                                    'price' => $this->cart->promo_discount, 'class' => 'cart-price', 'attribs' => 'data-cart-promo-discount', 'minus' => true]); ?>
                        </div>
                        <div class="info-line info-line-total">
                            <span><?php echo Text::_('COM_ISHOP_CART_SUM'); ?></span>
                            <?php echo LayoutHelper::render('itheme.product.price', [
                                    'price' => $this->cart->summary ?? 0, 'class' => 'cart-price', 'attribs' => 'data-cart-summary']); ?>
                        </div>
                    </div>

                    <?php if ($this->cart->total > 0) : ?>
                        <button class="btn btn-primary w-100 cart-checkout-button"
                                title="<?php echo Text::_('COM_ISHOP_CART_CHECKOUT'); ?>"
                                type="submit">
                            <span><?php echo Text::_('TPL_ITHEME_CART_GO_TO_CHECKOUT'); ?></span>
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-right']); ?>
                        </button>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
        <?php if ($this->cart->total > 0) : ?>
            <div class="mobile-action-bar cart-mobile-actions d-md-none"
                 data-cart-mobile-actions>
                <div class="container">
                    <div class="mobile-action-bar__inner">
                        <?php echo LayoutHelper::render('itheme.product.price', [
                                'price' => $this->cart->summary ?? 0,
                                'class' => 'mobile-action-bar__total',
                                'attribs' => 'data-cart-summary']); ?>
                        <button class="btn btn-primary mobile-action-bar__button cart-mobile-actions__button"
                                title="<?php echo Text::_('COM_ISHOP_CART_CHECKOUT'); ?>"
                                type="submit">
                            <span><?php echo Text::_('TPL_ITHEME_CART_GO_TO_CHECKOUT'); ?></span>
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-right']); ?>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
<?php else : ?>
    <div class="module-cart-empty">
        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-cart', 'class' => 'mega primary']); ?>
        <p><?php echo Text::_('COM_ISHOP_CART_NULL'); ?></p>
    </div>
<?php endif; ?>
