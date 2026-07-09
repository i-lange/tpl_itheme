<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Ilange\Component\Ishop\Site\Helper\ImageHelper;

/** @var Ilange\Component\Ishop\Site\View\Frontpage\HtmlView $this */
$wa = $this->getDocument()->getWebAssetManager();

$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
$analyticsItems = [];
foreach ($this->products as $i => $product) {
    $analyticsItems[] = [
            'item_id'       => $product->id,
            'item_name'     => $this->escape($product->fullname),
            'discount'      => $product->discount_size,
            'index'         => $i,
            'item_brand'    => $product->manufacturer_title,
            'price'         => ($product->sale_price > 0) ? $product->sale_price : $product->price,
            'quantity'      => 1,
    ];
}

$analyticsList = [
    'item_list_id'   => '0',
    'item_list_name' => Text::_('TPL_ITHEME_BEST_PRODUCTS'),
];
$analyticsContext = json_encode([
    'page'   => 'frontpage',
    'list'   => $analyticsList,
    'items'  => $analyticsItems,
    'source' => 'tpl_itheme.frontpage',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$analyticsEvent = json_encode([
    'event'    => 'view_item_list',
    'currency' => $currency,
    'list'     => $analyticsList,
    'items'    => $analyticsItems,
    'source'   => 'tpl_itheme.frontpage',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$wa->addInlineScript(
    'document.dispatchEvent(new CustomEvent("isiteanalytics:context",{bubbles:true,detail:' . $analyticsContext . '}));' .
    'document.dispatchEvent(new CustomEvent("isiteanalytics:ecommerce",{bubbles:true,detail:' . $analyticsEvent . '}));'
);
?>
<h2><?php echo Text::_('TPL_ITHEME_BEST_PRODUCTS'); ?></h2>

<?php if (count($this->products) > 0) : ?>
<div class="products__grid">
    <?php foreach ($this->products as $id => $item) : ?>
        <?php echo LayoutHelper::render('itheme.product.small', ['item' => $item]) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
