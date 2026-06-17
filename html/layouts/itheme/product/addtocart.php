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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);

/** @var object $item Объект товара */
/** @var string $class Дополнительный класс */

$params       = ComponentHelper::getParams('com_ishop');
$isSimple     = (bool) $params->get('cart_button_simple', true);
$isInCart     = !empty($item->incart);
$class        = htmlspecialchars('btn btn-light' . (!empty($class) ? ' ' . $class : '') . ($isInCart ? ' active' : ''), ENT_QUOTES, 'UTF-8');
$productId    = (int) ($item->id ?? 0);
$quantity     = max(1, (int) ($item->incart_count ?? 1));
$title        = htmlspecialchars(Text::_('TPL_ITHEME_BTN_BUY'), ENT_QUOTES, 'UTF-8');
$delivery     = htmlspecialchars((string) ($item->delivery ?? $title), ENT_QUOTES, 'UTF-8');
$buttonSimple = $isSimple ? 'true' : 'false';
?>
<?php if ($item->available) : ?>
    <?php $innerHtml = LayoutHelper::render('itheme.icon', ['icon' => 'i-cart']) . '<span>' . (($isSimple) ? '' : $delivery) . '</span>'; ?>

    <?php if ($isSimple || !$isInCart) : ?>
        <button
                class="<?php echo $class; ?>"
                title="<?php echo $title; ?>"
                data-tocart="<?php echo $productId; ?>"
                data-tocart-simple="<?php echo $buttonSimple; ?>"><?php echo $innerHtml; ?></button>
    <?php else : ?>
        <?php $class = trim($class . ' btn-control active'); ?>
        <button
                class="<?php echo $class; ?>"
                title="<?php echo $title; ?>"
                data-tocart="<?php echo $productId; ?>"
                data-tocart-simple="false"
                data-original-html="<?php echo htmlspecialchars($innerHtml, ENT_QUOTES, 'UTF-8'); ?>">
            <span class="btn_decrease">-</span>
            <span class="btn_quantity"><?php echo $quantity; ?></span>
            <span class="btn_increase">+</span>
        </button>
    <?php endif; ?>
<?php endif; ?>
