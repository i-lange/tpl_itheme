<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

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
// Подключим скрипт для работы вложенных панелей Offcanvas
$app->getDocument()->getWebAssetManager()->useScript('tpl.offcanvas-slider');
// Параметры текущего шаблона iTheme
$template = Factory::getApplication()->getTemplate(true)->params;
// Массив дочерних панелей
$subPanels = [];
?>
<section class="menu-panel is-active" id="off-panel-1" data-panel data-title="<?php echo Text::_('TPL_ITHEME_MAIN_MENU'); ?>" data-root>
<nav class="nav flex-column <?php echo $class_sfx; ?>">
    <?php foreach ($list as $i => &$item) {
        $itemParams = $item->getParams();
        $target = '';

        // Этот элемент родительский, а следующий на уровень глубже,
        // значит нужно добавить вызов вложенного меню
        if ($item->parent && $item->deeper) {
            $target = ' data-panel-target="off-panel-' . $item->alias . '"';
            $subPanels[$item->id]['title'] = $item->title;
            $subPanels[$item->id]['alias'] = $item->alias;
            $subPanels[$item->id]['parent'] = $item->parent_id;
            $subPanels[$item->id]['items'] = [];
        }

        // Этот элемент дочерний,
        // значит нужно его добавить во вложенную панель, но не выводить сразу
        if ($item->parent_id > 1) {
            //$target = ' data-panel-target="off-panel-' . $item->alias . '"';
            $subPanels[$item->parent_id]['items'][] = $item;
            continue;
        }

        switch ($item->type) {
            case 'heading':
                echo '<span class="nav-heading ', $item->anchor_css, '"' . $target . '>', $item->title, '</span>';
                break;
            case 'separator':
                echo '<span class="nav-link separator ', $item->anchor_css, '"' . $target . '>', $item->title,'</span>';
                break;
            default:
                require ModuleHelper::getLayoutPath('mod_menu', 'offcanvas_url');
                break;
        }
    }
    ?>
    <?php echo LayoutHelper::render('itheme.phone', ['params' => $template, 'class' => 'btn btn-lg btn-success mt-3','icon' => 'i-phone']); ?>
    <?php if ($url = $template->get('siteTelegram', false)) : ?>
        <a class="btn btn-lg btn-telegram mt-1"
           href="<?php echo $url; ?>"
           target="_blank"
           title="Telegram"
           aria-label="Telegram"
           rel="noopener noreferrer">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-telegram']); ?>
            <span>Telegram</span></a>
    <?php endif; ?>
    <?php if ($url = $template->get('siteViber', false)) : ?>
        <a class="btn btn-lg btn-viber mt-1"
           href="<?php echo $url; ?>"
           target="_blank"
           title="Viber"
           aria-label="Viber"
           rel="noopener noreferrer">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-viber']); ?>
            <span>Viber</span></a>
    <?php endif; ?>
</nav>
</section>
<?php foreach ($subPanels as $i => $panel) : ?>
<?php if (!empty($panel['items'])) : ?>
<section class="menu-panel" id="off-panel-<?php echo $panel['alias']; ?>" data-panel data-title="<?php echo $panel['title']; ?>" data-parent="off-panel-<?php echo $panel['parent']; ?>" aria-hidden="true">
    <button class="btn btn-back" type="button" data-panel-back aria-label="<?php echo Text::_('TPL_ITHEME_BACK'); ?>"><span><?php echo Text::_('TPL_ITHEME_BACK'); ?></span></button>
    <nav class="nav flex-column">
    <?php foreach ($panel['items'] as &$item) : ?>
    <?php
        $itemParams = $item->getParams();
        $target = '';
        // Этот элемент родительский, а следующий на уровень глубже,
        // значит нужно добавить вызов вложенного меню
        if ($item->parent && $item->deeper) {
            $target = ' data-panel-target="off-panel-' . $item->alias . '"';
        }
        switch ($item->type) {
            case 'heading':
                echo '<span class="nav-heading ', $item->anchor_css, '"' . $target . '>', $item->title, '</span>';
                break;
            case 'separator':
                echo '<span class="nav-link separator ', $item->anchor_css, '"' . $target . '>', $item->title,'</span>';
                break;
            default:
                require ModuleHelper::getLayoutPath('mod_menu', 'offcanvas_url');
                break;
        }
    ?>
    <?php endforeach; ?>
    </nav>
    </section>
<?php endif; ?>
<?php endforeach; ?>