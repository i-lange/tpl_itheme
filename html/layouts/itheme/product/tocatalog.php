<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

extract($displayData);

/** @var object $params Параметры магазина */
/** @var string $class Дополнительный класс */

// Если не передали параметры, заберем из компонента
if (empty($params)) {
    $params = ComponentHelper::getParams('com_ishop');
}
$class = htmlspecialchars('btn' . (!empty($class) ? ' ' . $class : ''), ENT_QUOTES, 'UTF-8');
?>
<a class="<?php echo $class; ?>"
   title="<?php echo Text::_('TPL_ITHEME_TO_CATALOG'); ?>"
   href="<?php echo Route::_(RouteHelper::getCategoriesRoute()); ?>">
    <?php echo Text::_('TPL_ITHEME_TO_CATALOG'); ?>
</a>
