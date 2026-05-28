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
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;

extract($displayData);

/** @var object $item Объект товара */
/** @var object $params Параметры магазина */

// Если не передали параметры, заберем из компонента
if (empty($params)) {
    $params = ComponentHelper::getParams('com_ishop');
}
?>
<div class="product_tools">
    <?php if ($params->get('use_wishlist', false)) : ?>
        <button class="btn<?php echo ($item->inwishlist) ? ' active' : ''; ?>"
                title="<?php echo Text::_('COM_ISHOP_WISHLIST_ADD'); ?>"
                data-towishlist="<?php echo $item->id; ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-like']); ?>
            <span class="visually-hidden"><?php echo Text::_('COM_ISHOP_WISHLIST_ADD'); ?></span>
        </button>
    <?php endif; ?>
    <?php if ($params->get('use_compare', false)) : ?>
        <button class="btn<?php echo ($item->incompare) ? ' active' : ''; ?>"
                title="<?php echo Text::_('COM_ISHOP_COMPARE_ADD'); ?>"
                data-tocompare="<?php echo $item->id; ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-compare']); ?>
            <span class="visually-hidden"><?php echo Text::_('COM_ISHOP_COMPARE_ADD'); ?></span>
        </button>
    <?php endif; ?>
</div>
