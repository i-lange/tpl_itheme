<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

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
 * @var int $count
 */

if ($params->get('use_js')) {
    $wa->useScript('mod_ishop_cart.front');
}

if ($params->get('use_css')) {
    $wa->useStyle('mod_ishop_cart.front');
}
?>
<a href="<?php echo Route::_(RouteHelper::getCartRoute()); ?>"
   class="header__button"
   aria-label="<?php echo Text::_('MOD_ISHOP_CART_COUNT'), ': ', $count; ?>"
   data-ishop-cart>
    <span class="header__button-icon-wrap" aria-hidden="true">
        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-cart']); ?>
        <?php if ($params->get('show_count', 0)) : ?>
            <small class="header__button-badge"><?php echo $count; ?></small>
        <?php endif; ?>
    </span>
    <?php if ($params->get('show_text', 0)) : ?>
        <span class="header__button-label"><?php echo Text::_('MOD_ISHOP_CART_TEXT'); ?></span>
    <?php endif; ?>
</a>