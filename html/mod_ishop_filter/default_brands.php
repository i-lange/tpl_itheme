<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

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

// Добавляем панель с выбором бренда
$subPanels['manufacturers']['title'] = Text::_('MOD_ISHOP_FILTER_BY_BRAND');
$subPanels['manufacturers']['alias'] = 'manufacturers';
// $subPanels['manufacturers']['values'] = $filter->manufacturers;
?>
<span class="nav-link separator" data-panel-target="off-panel-manufacturers"><?php echo Text::_('MOD_ISHOP_FILTER_BY_BRAND'); ?></span>