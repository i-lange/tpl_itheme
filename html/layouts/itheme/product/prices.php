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
use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);

/** @var object $item Объект товара */
/** @var object $params Параметры магазина */
/** @var string $class Дополнительный класс */

// Если не передали параметры, заберем из компонента
if (empty($params)) {
    $params = ComponentHelper::getParams('com_ishop');
}
$class = htmlspecialchars('product-price-box' . (!empty($class) ? ' ' . $class : ''), ENT_QUOTES, 'UTF-8');
$product_price = ($item->sale_price > 0) ? $item->sale_price : $item->price;
$currency = $params->get('defaultCurrency', 'BYN');
$icon = LayoutHelper::render('itheme.icon', ['icon' => 'i-' . $currency]);
$round = (int) $params->get('roundPrice', 0);

if ($item->price) : ?>
<div class="<?php echo $class; ?>">
    <div class="price <?php echo ($item->discount_size > 0) ? 'sale' : ''; ?>">
        <span><?php echo round($product_price, $round); ?></span><span class="currency" aria-label="<?php echo $currency; ?>"><?php echo $icon; ?></span>
    </div>
    <?php if ($item->old_price > 0) : ?>
        <div class="old-price">
            <del><?php echo round($item->old_price, $round); ?></del><span class="currency" aria-label="<?php echo $currency; ?>"><?php echo $icon; ?></span>
        </div>
    <?php endif; ?>
    <?php if ($item->discount_size > 0) : ?>
        <div class="badge text-bg-primary rounded-pill">-<?php echo $item->discount_size; ?>%</div>
    <?php endif; ?>
</div>
<?php endif;