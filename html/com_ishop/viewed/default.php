<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Ilange\Component\Ishop\Site\Helper\ImageHelper;

$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
$round = (int) $this->params->get('roundPrice', 0);
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <h1><?php echo $this->params->get('page_heading'); ?></h1>
<?php endif; ?>
<?php if ($this->viewed->count > 0) : ?>
    <div class="products__grid mb-5">
        <?php foreach ($this->viewed->products as $product) : ?>
            <?php echo LayoutHelper::render('itheme.product.small', ['item' => $product, 'params' => $this->params]) ?>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="d-flex flex-column min-vh-50 align-items-center justify-content-center">
        <h3 class="h1 text-body-tertiary"><?php echo Text::_('COM_ISHOP_VIEWED_NULL'); ?></h3>
        <?php echo LayoutHelper::render('itheme.product.tocatalog', ['class' => 'btn-lg btn-primary']); ?>
    </div>
<?php endif; ?>