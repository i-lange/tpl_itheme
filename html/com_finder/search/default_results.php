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
}
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
<div id="search-results" class="products__grid mb-5">
    <?php foreach ($results as $i => $item) : ?>
        <?php echo LayoutHelper::render('itheme.product.small', ['item' => $item]) ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php // Пагинация ?>
<?php if ($this->params->get('show_pagination', 1) > 0) : ?>
    <?php echo $this->pagination->getPagesLinks(); ?>
<?php endif; ?>
