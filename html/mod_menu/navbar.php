<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Список доступных переменных
 * @var stdClass $module
 * @var Joomla\CMS\Application\CMSApplicationInterface $app
 * @var Joomla\Input\Input $input
 * @var Joomla\Registry\Registry $params
 * @var stdClass $template
 * @var Joomla\CMS\WebAsset\WebAssetManager $wa
 * @var string $class_sfx
 * @var array $list
 * @var int $default_id
 * @var int $active_id
 * @var array $path
 */
$wa = $app->getDocument()->getWebAssetManager();
$startLevel = (int) $params->get('startLevel', 1);
?>
<ul class="nav header__navbar">
<?php foreach ($list as $i => &$item) {
    $itemParams = $item->getParams();
    echo '<li class="nav-item ' . $item->anchor_css . '">';

    switch ($item->type) :
        case 'separator':
        case 'heading':
            echo '<span class="nav-separator ', $item->anchor_css, '">', $item->title, '</span>';
            break;

        default:
            require ModuleHelper::getLayoutPath('mod_menu', 'navbar_url');
            break;
    endswitch;

    echo '</li>';
}
?>
</ul>
