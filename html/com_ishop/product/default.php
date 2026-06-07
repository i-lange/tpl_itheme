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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_ishop');

if ($this->params->get('use_js', true) && $this->params->get('use_cart', false)) {
    $wa->useScript('com_ishop.addtocart');
}

if ($this->params->get('use_js', true) && $this->params->get('use_compare', false)) {
    $wa->useScript('com_ishop.addtocompare');
}

if ($this->params->get('use_js', true) && $this->params->get('use_wishlist', false)) {
    $wa->useScript('com_ishop.addtowishlist');
}

// Сделаем ссылку на товар
$product = $this->item;
// Регистрируем просмотр карточки товара
$this->getModel()->hit($product->id);

$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
$dataLayerItems = [];
$dataLayerPrice = ($product->sale_price > 0) ? $product->sale_price : $product->price;
$dataLayerItems[] = [
    'item_id'       => $product->id,
    'item_name'     => $this->escape($product->fullname),
    'discount'      => $product->discount_size,
    'index'         => 1,
    'item_brand'    => $product->manufacturer_title,
    'item_category' => $product->category_title,
    'price'         => $dataLayerPrice,
    'quantity'      => 1,
];
$jsonLayerItems = json_encode($dataLayerItems, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$dataLayer = 'const dataLayerItems = ' . $jsonLayerItems . ';';
$wa->addInlineScript($dataLayer);
$dataLayer = 'gtag("event","view_item",{currency:"' . $currency . '",value:"' . $dataLayerPrice . '",items:dataLayerItems});';
$wa->addInlineScript($dataLayer);
?>
<div class="product-full container">
    <div class="row gy-2">
        <div class="col-md-5 col-lg-6 col-xl-7">
            <?php echo $this->loadTemplate('images'); ?>
        </div>
        <div class="col-md-7 col-lg-6 col-xl-5">
            <?php echo $this->loadTemplate('title'); ?>
            <?php echo $this->loadTemplate('buttons'); ?>
        </div>
    </div>
    <?php if (!empty($product->warehouses)) : ?>
    <h3 class="mt-3">Товар доступен в ПВЗ</h3>
    <div class="scroll-items-list list-20">
        <?php foreach ($product->warehouses as $warehouse) : ?>
            <?php echo LayoutHelper::render('itheme.warehouse', ['item' => $warehouse]) ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($this->item->fields) || !empty($this->item->fulltext)) : ?>
        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="productDescription" aria-labelledby="productDescriptionLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="productDescriptionLabel">Характеристики и описание</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body small">
                <div class="container container-sm">
                    <?php echo $this->loadTemplate('fields'); ?>
                    <?php echo $this->loadTemplate('description'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php echo $this->loadTemplate('parts'); ?>
</div>
<div class="bg-light py-5">
    <div class="container">
        <p><?php echo Text::_('COM_ISHOP_INFO_WARNING_01'); ?></p>
        <p><?php echo Text::_('COM_ISHOP_INFO_WARNING_02'); ?></p>
    </div>
</div>
