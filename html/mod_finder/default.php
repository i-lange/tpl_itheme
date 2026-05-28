<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/**
 * Список доступных переменных
 * @var stdClass $module
 * @var Joomla\CMS\Application\CMSApplicationInterface $app
 * @var Joomla\Input\Input $input
 * @var Joomla\Registry\Registry $params
 * @var stdClass $template
 * @var $route
 */

// Load the smart search component language file.
$lang = $app->getLanguage();
$lang->load('com_finder', JPATH_SITE);

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_finder');
$wa->usePreset('awesomplete');
$app->getDocument()->addScriptOptions('finder-search', ['url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component', false)]);
Text::script('COM_FINDER_SEARCH_FORM_LIST_LABEL');
Text::script('JLIB_JS_AJAX_ERROR_OTHER');
Text::script('JLIB_JS_AJAX_ERROR_PARSE');
Text::script('MOD_FINDER_SEARCH_VALUE');
$wa->useScript('com_finder.finder');
$finderHelper = $app->bootModule('mod_finder', 'site')->getHelper('FinderHelper');
?>
<search>
    <form class="mod-finder js-finder-searchform form-search" action="<?php echo Route::_($route); ?>" method="get" aria-label="search">
        <label for="mod-finder-searchword<?php echo $module->id; ?>"
               class="visually-hidden finder"><?php echo $params->get('alt_label', Text::_('JSEARCH_FILTER_SUBMIT')); ?></label>
        <input type="text"
               name="q"
               id="mod-finder-searchword<?php echo $module->id; ?>"
               class="js-finder-search-query form-control"
               value="<?php echo htmlspecialchars($app->getInput()->get('q', '', 'string'), ENT_COMPAT, 'UTF-8'); ?>"
               placeholder="<?php echo Text::_('MOD_FINDER_SEARCH_VALUE'); ?>">
        <button type="button" class="btn-close" aria-label="Close"></button>
        <button class="btn btn-primary" type="submit">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-search']); ?>
            <span class="visually-hidden"><?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?></span>
        </button>
        <?php echo $finderHelper->getHiddenFields($route); ?>
    </form>
</search>
