<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

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
 * @var array $zones
 * @var object $active_zone
 */
?>
<?php if ($params->get('show_active_zone', 0) && !empty($active_zone)) : ?>
    <button class="header__location btn btn-link btn-sm p-0"
            type="button"
            aria-label="<?php echo Text::_('MOD_ISHOP_ZONE_SELECT'); ?>"
            data-bs-toggle="modal" data-bs-target="#ishopZonesModal">
        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-geo']); ?>
        <span><?php echo $active_zone->title; ?></span>
    </button>
<?php endif; ?>
