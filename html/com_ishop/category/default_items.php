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
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$doc = $app->getDocument();
$wa = $doc->getWebAssetManager();
$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));

$dataLayerItems = [];
foreach ($this->items as $i => $product) {
    $dataLayerItems[] = [
        'item_id'       => $product->id,
        'item_name'     => $this->escape($product->fullname),
        'discount'      => $product->discount_size,
        'index'         => $i,
        'item_brand'    => $product->manufacturer_title,
        'item_category' => $this->category->title,
        'price'         => ($product->sale_price > 0) ? $product->sale_price : $product->price,
        'quantity'      => 1,
    ];
}
$jsonLayerItems = json_encode($dataLayerItems, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$dataLayer = 'const dataLayerItems = ' . $jsonLayerItems . ';';
$wa->addInlineScript($dataLayer);
$dataLayer = 'gtag("event","view_item_list",{currency:"' . $currency . '",item_list_id:"' . $this->category->id . '",item_list_name:"' . $this->category->title . '",items:dataLayerItems});';
$wa->addInlineScript($dataLayer);
?>
<?php foreach ($this->items as $product) : ?>
    <?php echo LayoutHelper::render('itheme.product.small', ['item' => $product, 'params' => $this->params]) ?>
<?php endforeach; ?>
