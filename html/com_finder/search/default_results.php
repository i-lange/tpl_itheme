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
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */
$app = Factory::getApplication();

if ($this->total > 0) {
    $lang = $app->getLanguage();
    $lang->load('com_ishop', JPATH_SITE);

    $ids = array_column($this->results, 'id');
    $model = $app
            ->bootComponent('com_ishop')
            ->getMVCFactory()
            ->createModel('Products', 'Site', ['ignore_request' => true]);

    $model->setState('filter.warehouse_id', false);
    $model->setState('params', $app->getParams());

    $model->setState('filter.published', 1);
    $model->setState('filter.access', 1);
    $model->setState('filter.language', Multilanguage::isEnabled());

    // Добавляем фильтрацию по списку товаров в корзине
    $model->setState('filter.products', $ids);

    $model->setState('list.ordering', 'FIELD(a.id, '. implode(',', $ids) . ')');
    $model->setState('list.direction', '');
    $model->setState('list.limit', 0);

    $results = $model->getItems();

    $currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
    $dataLayerItems = [];

    foreach ((array) $results as $i => $product) {
        $dataLayerItems[] = [
            'item_id'       => (int) $product->id,
            'item_name'     => $this->escape($product->fullname),
            'discount'      => (float) $product->discount_size,
            'index'         => (int) $this->pagination->limitstart + $i,
            'item_brand'    => (string) $product->manufacturer_title,
            'item_category' => (string) $product->category_title,
            'price'         => ((float) $product->sale_price > 0) ? (float) $product->sale_price : (float) $product->price,
            'quantity'      => 1,
        ];
    }

    $jsonLayerItems = json_encode($dataLayerItems, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $jsonListName = json_encode((string) $this->query->input, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $dataLayer = 'window.iThemePendingEcommerceItems=(window.iThemePendingEcommerceItems||[]).concat(' . $jsonLayerItems . ');';
    $dataLayer .= 'if(window.iTheme&&typeof window.iTheme.registerEcommerceItems==="function"){window.iTheme.registerEcommerceItems(' . $jsonLayerItems . ');}';
    $dataLayer .= 'if(typeof gtag==="function"){gtag("event","view_item_list",{currency:"' . $currency . '",item_list_id:"finder",item_list_name:' . $jsonListName . ',items:' . $jsonLayerItems . '});}';
    $app->getDocument()->getWebAssetManager()->addInlineScript($dataLayer);
}

$limit = max(1, (int) $this->pagination->limit);
$limitstart = (int) $this->pagination->limitstart;
$total = (int) $this->pagination->total;
$nextLimitstart = $limitstart + $limit;
$hasMore = !empty($results) && $nextLimitstart < $total;
$loaderStateId = 'ishop-finder-loader-state';
$loaderState = [
    'Itemid' => $app->getInput()->getInt('Itemid', 0),
    'q'      => (string) $this->query->input,
    'f'      => (int) $app->getInput()->getInt('f', $this->query->filter ?? 0),
    't'      => $app->getInput()->get('t', [], 'array'),
    'l'      => (string) $app->getInput()->getCmd('l', $this->query->language ?? $app->getLanguage()->getTag()),
    'd1'     => (string) $app->getInput()->getString('d1', $this->query->date1 ?? ''),
    'd2'     => (string) $app->getInput()->getString('d2', $this->query->date2 ?? ''),
    'w1'     => (string) $app->getInput()->getString('w1', $this->query->when1 ?? ''),
    'w2'     => (string) $app->getInput()->getString('w2', $this->query->when2 ?? ''),
    'o'      => (string) $app->getInput()->getWord('o', $this->state->get('list.raworder', 'relevance')),
    'od'     => strtolower((string) $app->getInput()->getWord('od', $this->state->get('list.direction', 'DESC'))),
];
?>
<?php // Выводим сообщение 'нет результатов' и выходим из шаблона ?>
<?php if (($this->total === 0) || ($this->total === null)) : ?>
    <div id="search-result-empty" class="mb-5">
        <p><?php echo Text::_('COM_FINDER_SEARCH_NO_RESULTS_HEADING'); ?></p>
        <?php $multilang = Factory::getApplication()->getLanguageFilter() ? '_MULTILANG' : ''; ?>
        <div class="alert alert-warning" role="alert"><?php echo Text::sprintf('COM_FINDER_SEARCH_NO_RESULTS_BODY' . $multilang, $this->escape($this->query->input)); ?></div>
    </div>
    <?php return; ?>
<?php endif; ?>

<?php // Вывод списка результатов ?>
<?php $total = (int) $this->pagination->total; ?>
<?php if ($total > 0) : ?>
<p>Товаров: <?php echo $total; ?></p>
<div id="search-results"
     class="products__grid mb-5"
     data-ishop-products
     data-ishop-context="finder"
     data-ishop-endpoint="<?php echo Route::_('index.php?option=com_ishop&task=products.load&format=json', false); ?>"
     data-ishop-state="<?php echo $loaderStateId; ?>"
     data-ishop-token="<?php echo Session::getFormToken(); ?>"
     data-ishop-limit="<?php echo $limit; ?>"
     data-ishop-total="<?php echo $total; ?>"
     data-ishop-next-limitstart="<?php echo $nextLimitstart; ?>"
     data-ishop-has-more="<?php echo $hasMore ? '1' : '0'; ?>"
     data-ishop-currency="<?php echo strtoupper($this->params->get('defaultCurrency', 'BYN')); ?>">
    <?php foreach ($results as $i => $item) : ?>
        <?php echo LayoutHelper::render('itheme.product.small', ['item' => $item]) ?>
    <?php endforeach; ?>
</div>
<script type="application/json" id="<?php echo $loaderStateId; ?>"><?php echo json_encode($loaderState, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
<?php endif; ?>
