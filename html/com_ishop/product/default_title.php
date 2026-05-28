<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Ilange\Component\Ishop\Site\Helper\FormatHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
?>
<?php if ($this->item->discount_size > 0) : ?>
<div>
    <span class="product__label label_size">-<?php echo $this->item->discount_size; ?>%</span>
    <span class="product__label label_price"><?php echo Text::_('COM_ISHOP_PRODUCT_GOOD_PRICE'); ?></span>
</div>
<?php endif; ?>
<h1 class="product-full__title">
    <span class="prefix"><?php echo $this->item->prefix; ?></span><br>
    <span class="manufacturer"><?php echo $this->item->manufacturer_title; ?></span>
    <span class="model"> / <?php echo $this->item->title; ?></span></h1>
<?php echo LayoutHelper::render('itheme.product.tools-full', ['item' => $this->item]); ?>
<?php if ($this->item->introtext !== '') : ?>
    <blockquote class="blockquote d-n d-md-block">
        <p><?php echo $this->item->introtext; ?></p>
    </blockquote>
<?php else: ?>
    <blockquote class="blockquote text-lowercase d-n d-md-block">
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
        <p><?php echo implode(', ', $find); ?></p>
    <?php endif; ?>
    </blockquote>
<?php endif; ?>
<?php if (!empty($this->item->fields) || !empty($this->item->fulltext)) : ?>
    <button class="btn btn-light btn-lg border mb-3"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#productDescription"
            aria-controls="productDescription"
            aria-label="Характеристики и описание">Характеристики и описание</button>
<?php endif; ?>
