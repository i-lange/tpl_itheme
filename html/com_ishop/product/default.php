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
        <div class="col-12 col-md-7 col-lg-5 col-xl-6">
            <?php echo $this->loadTemplate('images'); ?>
        </div>
        <div class="col-12 col-md-5 col-lg-7 col-xl-6">
            <?php echo $this->loadTemplate('title'); ?>
            <div class="row gy-2">
                <div class="col-12 col-sm-5 col-md-12 col-lg-5 col-xl-6" id="product-offers"><?php echo $this->loadTemplate('offers'); ?></div>
                <div class="col-12 col-sm-7 col-md-12 col-lg-7 col-xl-6" id="product-buttons"><?php echo $this->loadTemplate('buttons'); ?></div>
            </div>
        </div>
    </div>
    <?php echo $this->loadTemplate('description'); ?>
    <?php echo $this->loadTemplate('fields'); ?>
    <?php if (!empty($product->warehouses)) : ?>
    <h3 class="mt-5">Наличие в магазинах и ПВЗ</h3>
    <div class="scroll-items-list list-20">
        <?php foreach ($product->warehouses as $warehouse) : ?>
            <?php echo LayoutHelper::render('itheme.product.warehouse', ['item' => $warehouse, 'product' => $product]); ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php echo $this->loadTemplate('parts'); ?>
</div>
<?php if (!empty($product->related)) : ?>
<div class="container mt-5">
    <h2>С этим товаром покупают</h2>
    <div class="products__grid">
        <?php foreach ($product->related as $item) : ?>
            <?php echo LayoutHelper::render('itheme.product.small', ['item' => $item, 'params' => $this->params]) ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<?php if (!empty($product->similar)) : ?>
<div class="container mt-5">
    <h2>Похожие товары</h2>
    <div class="products__grid">
        <?php foreach ($product->similar as $item) : ?>
            <?php echo LayoutHelper::render('itheme.product.small', ['item' => $item, 'params' => $this->params]) ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<div class="product-full__warning mt-5">
    <div class="container">
        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-message', 'class' => 'd-none d-md-inline-block']); ?>
        <div>
            <p><?php echo Text::_('COM_ISHOP_INFO_WARNING_01'); ?></p>
            <p><?php echo Text::_('COM_ISHOP_INFO_WARNING_02'); ?></p>
        </div>
    </div>
</div>
