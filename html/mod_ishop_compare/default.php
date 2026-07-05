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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;

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
 * @var int $wishlist
 */

if ($params->get('use_js', 0)) {
    $wa->useScript('mod_ishop_compare.front');
}

if ($params->get('use_css', 0)) {
    $wa->useStyle('mod_ishop_compare.front');
}
?>
<a href="<?php echo Route::_(RouteHelper::getCompareRoute()); ?>"
   class="header__button"
   aria-label="<?php echo Text::_('MOD_ISHOP_COMPARE_COUNT'), ': ', $count; ?>"
   data-ishop-compare>
    <span class="header__button-icon-wrap" aria-hidden="true">
        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-compare-lg']); ?>
        <?php if ($params->get('show_count', 0)) : ?>
            <small class="badge rounded-pill header__button-badge"><?php echo $count; ?></small>
        <?php endif; ?>
    </span>
    <?php if ($params->get('show_text', 0)) : ?>
        <span class="header__button-label"><?php echo Text::_('MOD_ISHOP_COMPARE_TEXT'); ?></span>
    <?php endif; ?>
</a>