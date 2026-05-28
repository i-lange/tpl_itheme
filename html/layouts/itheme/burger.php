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
use Joomla\CMS\Layout\LayoutHelper;
?>
<button class="header__button"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#mobileMenu"
        aria-controls="mobileMenu"
        aria-label="<?php echo Text::_('TPL_ITHEME_MENU_ARIA_LABEL'); ?>">
    <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-burger']); ?>
    <span class="header__button-label"><?php echo Text::_('TPL_ITHEME_MENU'); ?></span>
</button>