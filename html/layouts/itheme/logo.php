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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Доступные переменные
 * @var object $params
 * @var string $class
 */

// Если не передали параметры, заберем из шаблона
if (empty($params)) {
    $params = Factory::getApplication()->getTemplate(true)->params;
}

if ($file = $params->get('logoFile')) {
    $logo = HTMLHelper::cleanImageURL($file);
} else {
    $logo = Text::_('TPL_ITHEME_LOGO_TEXT');
}

$class = !empty($class) ? 'class="' . $class . '"' : '';
?>
<div <?php echo $class; ?> aria-label="<?php echo Text::_('TPL_ITHEME_GO_FRONTPAGE'); ?>">
    <?php if ($file = $params->get('logoFile')) : ?>
        <img src="<?php echo $logo->url; ?>"
             width="<?php echo $logo->attributes['width']; ?>"
             height="<?php echo $logo->attributes['height']; ?>"
             alt="<?php echo Text::_('TPL_ITHEME_SITENAME'); ?>"
             data-logo-click/>
    <?php else : ?>
        <span data-logo-click><?php echo $logo; ?></span>
    <?php endif; ?>
</div>