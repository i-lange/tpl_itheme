<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

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

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->useScript('bootstrap.modal');
?>
<div class="modal fade" id="ishopZonesModal" tabindex="-1" aria-labelledby="ishopZonesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ishopZonesModalLabel"><?php echo Text::_('MOD_ISHOP_ZONE_SELECT'); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo Text::_('TPL_ITHEME_CLOSE'); ?>"></button>
            </div>
            <div class="modal-body">
                <form class="row row-cols-1 row-cols-md-2"
                      action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
                      method="post"
                      name="ishop-zone-select">
                    <?php foreach ($zones as $i => $zone): ?>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="radio"
                                       name="active_zone"
                                       value="<?php echo $zone->id; ?>"
                                       id="active_zone_radio_<?php echo $i; ?>"
                                       onchange="this.form.submit()"
                                       <?php echo ($active_zone->id === $i) ? 'checked' : ''; ?>>
                                <label class="form-check-label"
                                       for="active_zone_radio_<?php echo $i; ?>"><?php echo $zone->title; ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>
    </div>
</div>
