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

extract($displayData);

/** @var object $item Объект товара */
/** @var string $class Дополнительный класс */

$class = !empty($class) ? ' ' . $class : '';
?>
<?php if ($item->available) : ?>
    <?php if ($item->incart) : ?>
        <button class="btn btn-primary<?php echo $class; ?> active"
                title="<?php echo Text::_('COM_ISHOP_ADD_TO_CART'); ?>"
                data-tocart="<?php echo $item->id; ?>"
                data-original-html="<?php echo $this->escape(LayoutHelper::render('itheme.icon', ['icon' => 'i-cart'])); ?><span><?php echo $item->delivery; ?></span>">
            <span class="btn_decrease">-</span>
            <span class="btn_quantity"><?php echo $item->incart_count; ?></span>
            <span class="btn_increase">+</span>
        </button>
    <?php else : ?>
        <button class="btn btn-primary<?php echo $class; ?>"
                title="<?php echo Text::_('COM_ISHOP_ADD_TO_CART'); ?>"
                data-tocart="<?php echo $item->id; ?>"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-cart']); ?><span><?php echo $item->delivery; ?></span></button>
    <?php endif; ?>
<?php endif; ?>
