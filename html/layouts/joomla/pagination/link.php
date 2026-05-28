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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var array $displayData */

$item    = $displayData['data'];
$display = $item->text;
$app = Factory::getApplication();

switch ((string) $item->text) {
    // Check for "Start" item
    case Text::_('JLIB_HTML_START'):
        $icon = $app->getLanguage()->isRtl() ? 'i-chevron-right' : 'i-chevron-left-d';
        $aria = Text::sprintf('JLIB_HTML_GOTO_POSITION', strtolower($item->text));
        break;

    // Check for "Prev" item
    case $item->text === Text::_('JPREV'):
        $item->text = Text::_('JPREVIOUS');
        $icon = $app->getLanguage()->isRtl() ? 'i-chevron-right' : 'i-chevron-left';
        $aria = Text::sprintf('JLIB_HTML_GOTO_POSITION', strtolower($item->text));
        break;

    // Check for "Next" item
    case Text::_('JNEXT'):
        $icon = $app->getLanguage()->isRtl() ? 'i-chevron-left' : 'i-chevron-right';
        $aria = Text::sprintf('JLIB_HTML_GOTO_POSITION', strtolower($item->text));
        break;

    // Check for "End" item
    case Text::_('JLIB_HTML_END'):
        $icon = $app->getLanguage()->isRtl() ? 'i-chevron-left-d' : 'i-chevron-right-d';
        $aria = Text::sprintf('JLIB_HTML_GOTO_POSITION', strtolower($item->text));
        break;

    default:
        $icon = null;
        $aria = Text::sprintf('JLIB_HTML_GOTO_PAGE', strtolower($item->text));
        break;
}

if ($icon !== null) {
    $display = LayoutHelper::render('itheme.icon', ['icon' => $icon]);
}

if ($displayData['active']) {
    if ($item->base > 0) {
        $limit = 'limitstart.value=' . $item->base;
    } else {
        $limit = 'limitstart.value=0';
    }

    $class = 'active';

    if ($app->isClient('administrator')) {
        $link = 'href="#" onclick="document.adminForm.' . $item->prefix . $limit . '; Joomla.submitform();return false;"';
    } elseif ($app->isClient('site')) {
        $link = 'href="' . $item->link . '"';
    }
} else {
    $class = (property_exists($item, 'active') && $item->active) ? 'active' : 'disabled';
}

?>
<?php if ($displayData['active']) : ?>
    <li class="page-item">
        <a aria-label="<?php echo $aria; ?>" <?php echo $link; ?> class="page-link">
            <?php echo $display; ?>
        </a>
    </li>
<?php elseif (isset($item->active) && $item->active) : ?>
    <?php $aria = Text::sprintf('JLIB_HTML_PAGE_CURRENT', strtolower($item->text)); ?>
    <li class="<?php echo $class; ?> page_item">
        <a aria-current="true" aria-label="<?php echo $aria; ?>" href="#" class="page-link"><?php echo $display; ?></a>
    </li>
<?php else : ?>
    <li class="<?php echo $class; ?> page-item">
        <span class="page-link" aria-hidden="true"><?php echo $display; ?></span>
    </li>
<?php endif; ?>
