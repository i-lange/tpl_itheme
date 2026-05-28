<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * Список доступных переменных
 * @var stdClass $module
 * @var Joomla\CMS\Application\CMSApplicationInterface $app
 * @var Joomla\Input\Input $input
 * @var Joomla\Registry\Registry $params
 * @var stdClass $template
 * @var Joomla\CMS\WebAsset\WebAssetManager $wa
 * @var string $captcha
 * @var object $filter
 */

$access_levels = Factory::getApplication()->getIdentity()->getAuthorisedViewLevels();
$warehouses_access = $params->get('warehouses_access', 0);
?>
<?php if ($warehouses_access > 0 && in_array($warehouses_access, $access_levels)) : ?>
    <?php
    // Добавляем панель с выбором склада
    $subPanels['warehouses']['title'] = Text::_('MOD_ISHOP_FILTER_BY_WAREHOUSES');
    $subPanels['warehouses']['alias'] = 'warehouses';
    ?>
    <span class="nav-link separator" data-panel-target="off-panel-warehouses"><?php echo Text::_('MOD_ISHOP_FILTER_BY_WAREHOUSES'); ?></span>
<?php endif; ?>