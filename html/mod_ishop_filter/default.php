<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
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
 * @var object $filter
 */

if (empty($filter) || $filter->empty) {
    return;
}

$wa->useScript('bootstrap.offcanvas');

$formId = 'i-filter-' . $module->id;

// Массив дочерних панелей
$subPanels = [];
?>
<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="moduleFilter"
     aria-labelledby="moduleFilterLabel"
     data-offcanvas-panels>
    <div class="offcanvas-header border-bottom">
        <div class="offcanvas-title-wrap">
            <?php echo LayoutHelper::render('itheme.logo', ['class' =>'offcanvas-logo', 'alt' => Text::_('TPL_ITHEME_SITENAME')]); ?>
            <h3 class="offcanvas-title" id="moduleFilterLabel"><?php echo Text::_('TPL_ITHEME_FILTER_ANCHOR'); ?></h3>
        </div>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="<?php echo Text::_('TPL_ITHEME_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
    <form class="menu-viewport" data-menu-viewport
      action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
      method="post"
      name="ishop_filter"
      id="<?php echo $formId; ?>">
        <section class="menu-panel is-active" id="off-panel-filter" data-panel data-title="<?php echo Text::_('TPL_ITHEME_FILTER_ANCHOR'); ?>" data-root>
        <nav class="nav flex-column">
        <?php if ($params->get('show_prices', 0)) : ?>
            <?php require ModuleHelper::getLayoutPath('mod_ishop_filter', $params->get('layout', 'default') . '_prices'); ?>
        <?php endif; ?>
        <?php if ($params->get('show_sales', 0)) : ?>
            <?php require ModuleHelper::getLayoutPath('mod_ishop_filter', $params->get('layout', 'default') . '_sales'); ?>
        <?php endif; ?>
        <?php if ($params->get('show_warehouses', 0)) : ?>
            <?php require ModuleHelper::getLayoutPath('mod_ishop_filter', $params->get('layout', 'default') . '_warehouses'); ?>
        <?php endif; ?>
        <?php if ($params->get('show_brand', 0) && count($filter->manufacturers) > 1) : ?>
            <?php require ModuleHelper::getLayoutPath('mod_ishop_filter', $params->get('layout', 'default') . '_brands'); ?>
        <?php endif; ?>
        <?php require ModuleHelper::getLayoutPath('mod_ishop_filter', $params->get('layout', 'default') . '_sizes'); ?>
        <?php if ($params->get('show_fields', 0) && count($filter->ishop_fields) > 0) : ?>
            <?php require ModuleHelper::getLayoutPath('mod_ishop_filter', $params->get('layout', 'default') . '_fields'); ?>
        <?php endif; ?>
        </nav>
        </section>
        <?php foreach ($subPanels as $id => $panel) : ?>
        <?php $noFields = ['warehouses', 'manufacturers']; ?>
        <section class="menu-panel" id="off-panel-<?php echo $panel['alias']; ?>" data-panel data-title="<?php echo $panel['title']; ?>" data-parent="off-panel-filter" aria-hidden="true">
            <button class="btn btn-back" type="button" data-panel-back aria-label="<?php echo Text::_('TPL_ITHEME_BACK'); ?>"><span><?php echo Text::_('TPL_ITHEME_BACK'); ?></span></button>
            <?php if (in_array($id, $noFields)) : ?>
                <input type="hidden" name="<?php echo $id; ?>[]" value="0">
                <?php foreach($filter->$id as $variant) : ?>
                    <div class="form-check">
                        <?php
                        $checked = '';
                        if (in_array($variant->id, $filter->active[$id])) {
                            $checked = 'checked';
                        }
                        ?>
                        <input class="form-check-input"
                               id="<?php echo $id, ' ', $variant->id; ?>"
                               type="checkbox"
                               name="warehouses[]"
                               value="<?php echo $variant->id; ?>" <?php echo $checked; ?>>
                        <label class="form-check-label"
                               for="<?php echo $id, ' ', $variant->id; ?>"><?php echo $variant->title; ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            <input type="hidden" name="ishop_fields[<?php echo $id; ?>][]" value="0">
            <?php foreach($panel['values'] as $value_id => $value) : ?>
                <div class="form-check">
                    <?php
                    $checked = '';
                    if (isset($filter->active['fields'][$id]) && in_array($value_id, $filter->active['fields'][$id])) {
                        $checked = 'checked';
                    }
                    ?>
                    <input class="form-check-input"
                           id="value-<?php echo $id . '-' . $value_id; ?>"
                           type="checkbox"
                           name="ishop_fields[<?php echo $id; ?>][]"
                           value="<?php echo $value_id; ?>" <?php echo $checked; ?>>
                    <label class="form-check-label"
                           for="value-<?php echo $id . '-' . $value_id; ?>">
                        <?php echo $value ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </section>
        <?php endforeach; ?>
    </form>
    </div>
    <div class="offcanvas-footer border-top">
        <button class="btn btn-primary me-2" type="submit" form="<?php echo $formId; ?>"><?php echo Text::_('MOD_ISHOP_FILTER_MODULE_SUBMIT'); ?></button>
        <button class="btn btn-link" type="button" id="resetFilterBtn"><?php echo Text::_('MOD_ISHOP_FILTER_MODULE_RESET'); ?></button>
    </div>
</div>