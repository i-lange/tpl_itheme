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
//$wa->getRegistry()->addExtensionRegistryFile('mod_menu');
//$wa->usePreset('mod_menu.menu');

$startLevel = (int) $params->get('startLevel', 1);
?>
<ul class="nav <?php echo $class_sfx; ?>">
<?php foreach ($list as $i => &$item) {
    $itemParams = $item->getParams();
    $class      = 'nav-item';

    if ($item->id == $default_id) {
        $class .= ' default';
    }

    if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) {
        $class .= ' current';
    }

    if (in_array($item->id, $path)) {
        $class .= ' active';
    } elseif ($item->type === 'alias') {
        $aliasToId = $itemParams->get('aliasoptions');

        if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
            $class .= ' active';
        } elseif (in_array($aliasToId, $path)) {
            $class .= ' alias-parent-active';
        }
    }

    if ($item->type === 'separator') {
        $class .= ' divider';
    }

    if ($item->deeper) {
        $class .= ' deeper';
    }

    if ($item->parent) {
        $class .= ' parent';
    }

    echo '<li class="' . $class . '">';

    // Следующий элемент вложенный — добавим выпадающее меню здесь, если это заголовок или разделитель
    if ($item->deeper && (int) $item->level === $startLevel && in_array($item->type, ['separator', 'heading'])) {
        // Кнопка выпадающего меню
        echo '<button class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
    }

    switch ($item->type) :
        case 'separator':
        case 'component':
        case 'heading':
        case 'url':
            require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
            break;

        default:
            require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
            break;
    endswitch;

    // Следующий элемент вложенный
    if ($item->deeper) {
        // Проверим тип - только на первом уровне
        if ((int) $item->level === $startLevel) {
            switch ($item->type) {
                case 'heading':
                case 'separator':
                    echo '</button>';
                    break;

                default:
                    echo '<button class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">' .
                    '<span class="visually-hidden">' . Text::sprintf('MOD_MENU_TOGGLE_SUBMENU_LABEL', $item->title) . '</span>' .
                    '</button>';
            }
        }
        echo '<ul class="dropdown-menu">';
    } elseif ($item->shallower) {
        // Следующий элемент выше
        echo '</li>';
        echo str_repeat('</ul></li>', $item->level_diff);
    } else {
        // Следующий элемент на том же уровне
        echo '</li>';
    }
}
?></ul>
