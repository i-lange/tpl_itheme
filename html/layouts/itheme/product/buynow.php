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
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;

extract($displayData);

/** @var object $item Объект товара */
/** @var string $class Дополнительный класс */

$class = !empty($class) ? ' ' . $class : '';
?>
<?php if ($item->available) : ?>
    <form action="<?php echo Route::_(RouteHelper::getCheckoutRoute()); ?>"
          method="post"
          name="product-buy-now">
        <input type="hidden" name="products[]" value="<?php echo $item->id; ?>">
        <button class="btn btn-warning <?php echo $class; ?>"
                title="<?php echo Text::_('COM_ISHOP_PRODUCT_BUY_NOW'); ?>"
                type="submit"><?php echo Text::_('COM_ISHOP_PRODUCT_BUY_NOW'); ?></button>
    </form>
<?php endif; ?>
