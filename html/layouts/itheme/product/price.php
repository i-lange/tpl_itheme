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

/** @var float $price Цена */
/** @var object $params Параметры магазина */
/** @var string $class Дополнительный класс */
/** @var string $attribs Дополнительные атрибуты */

// Если не передали параметры, заберем из компонента
if (empty($params)) {
    $params = ComponentHelper::getParams('com_ishop');
}
if (empty($price)) {
    $price = 0;
}
$class = htmlspecialchars((!empty($class) ? ' ' . $class : ''), ENT_QUOTES, 'UTF-8');
$attribs = htmlspecialchars((!empty($attribs) ? ' ' . $attribs : ''), ENT_QUOTES, 'UTF-8');
$currency = $params->get('defaultCurrency', 'BYN');
$icon = LayoutHelper::render('itheme.icon', ['icon' => 'i-' . $currency]);
$round = (int) $params->get('roundPrice', 0);
$formatPrice = static function ($price) use ($round): string {
    $price = round((float) $price, $round);

    if ($round <= 0) {
        return number_format($price, 0, ',', ' ');
    }

    return rtrim(rtrim(number_format($price, $round, ',', ' '), '0'), ',');
};
?>
<div class="<?php echo $class; ?>">
    <?php echo (!empty($minus) && $minus === true) ? '-' : ''; ?><span<?php echo $attribs; ?>><?php echo $formatPrice($price); ?></span><span class="currency" aria-label="<?php echo $currency; ?>"><?php echo $icon; ?></span>
</div>
