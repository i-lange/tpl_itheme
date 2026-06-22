<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\FormatHelper;
use Joomla\CMS\Language\Text;

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */

?>
<?php if (!empty($this->item->fields)) : ?>
    <div class="offcanvas-md offcanvas-end" tabindex="-1" id="productFields" aria-labelledby="productFieldsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="productFieldsLabel"><?php echo Text::_('COM_ISHOP_PRODUCT_ALL_FIELDS'); ?></h5>
            <button type="button" class="btn-close d-md-none" data-bs-dismiss="offcanvas" data-bs-target="#productFields" aria-label="Закрыть характеристики"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <h2 class="d-none d-md-block mt-5"><?php echo Text::_('COM_ISHOP_PRODUCT_ALL_FIELDS'); ?></h2>
                <?php foreach($this->item->fields as $group) : ?>
                    <?php if (empty($group->fields)) continue; ?>
                    <div class="product-full__fields">
                        <h4 class="mb-3"><?php echo $group->title; ?></h4>
                        <?php foreach($group->fields as $field) : ?>
                            <div class="product-full__field">
                                <span><?php echo $field->field_title; ?>:</span>
                                <span>
                                    <?php if ($field->field_type === 2) : ?>
                                        <?php echo ($field->field_value === 'y') ? Text::_('COM_ISHOP_YES') : Text::_('COM_ISHOP_NO'); ?>
                                    <?php else : ?>
                                        <?php if ($field->field_type === 0) $field->field_value = FormatHelper::renderFloat($field->field_value); ?>
                                        <?php echo $field->field_value, ' ', ($field->field_unit !== '') ? ' ' . $field->field_unit : ''; ?>
                                    <?php endif; ?>
                                    <?php echo ($field->field_value_hint !== '') ? ' (' . $field->field_value_hint . ')' : ''; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif;