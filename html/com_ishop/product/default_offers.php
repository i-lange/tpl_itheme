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
use Joomla\CMS\Layout\LayoutHelper;

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */

?>
<?php if (!empty($this->item->offers)) : ?>
    <div class="small mb-2">Модификации</div>
    <div class="product-full__offers">
    <?php echo LayoutHelper::render('itheme.product.thumb', ['item' => $this->item, 'class' => 'active']); ?>
<?php foreach($this->item->offers as $offer) : ?>
    <?php echo LayoutHelper::render('itheme.product.thumb', ['item' => $offer, 'class' => '']); ?>
<?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if ($this->item->introtext !== '') : ?>
    <p class="mt-3 d-n d-md-block text-body-emphasis"><?php echo $this->item->introtext; ?></p>
<?php else: ?>
    <p class="mt-3 text-lowercase d-n d-md-block text-body-emphasis">
        <?php
        $important = $this->params->get('important_fields', []);
        $find = [];
        $first = true;

        foreach($this->item->fields as $groups) {
            foreach($groups->fields as $field_id => $field) {
                if (in_array($field_id, $important)) {
                    if ($field->field_type === 2) {
                        if ($field->field_value === 'y') {
                            if (!$first) echo ', ';
                            echo $field->field_title . ' есть';
                            $first = false;
                        }
                    }
                    else {
                        if (!$first) echo ', ';
                        echo $field->field_title . ' ';
                        if ($field->field_type === 0) $field->field_value = FormatHelper::renderFloat($field->field_value);
                        echo $field->field_value, ($field->field_unit !== '') ? ' ' . $field->field_unit : '';
                        $first = false;
                    }
                    echo ($field->field_value_hint !== '') ? ' (' . $field->field_value_hint . ')' : '';
                }
            }
        }
        ?>
        <?php if (!empty($find)) : ?>
            <?php echo implode(', ', $find); ?>
        <?php endif; ?>
    </p>
<?php endif; ?>
<?php if (!empty($this->item->fulltext)) : ?>
    <button class="btn btn-nav-link btn-link d-md-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#productDescription"
            aria-controls="productDescription"
            aria-label="<?php echo Text::_('COM_ISHOP_PRODUCT_DESCRIPTIONS'); ?>"
            title="Открыть описание товара"><?php echo Text::_('COM_ISHOP_PRODUCT_DESCRIPTIONS'); ?></button>
<?php endif; ?>
<?php if (!empty($this->item->fields)) : ?>
    <button class="btn btn-nav-link btn-link d-md-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#productFields"
            aria-controls="productFields"
            aria-label="<?php echo Text::_('COM_ISHOP_PRODUCT_ALL_FIELDS'); ?>"
            title="Открыть характеристики товара"><?php echo Text::_('COM_ISHOP_PRODUCT_ALL_FIELDS'); ?></button>
<?php endif; ?>
<?php if (!empty($this->item->documents)) : ?>
    <div class="product-full__documents mt-3">
        <?php foreach($this->item->documents as $document) : ?>
            <?php echo LayoutHelper::render('itheme.document', ['item' => $document, 'class' => '']); ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>