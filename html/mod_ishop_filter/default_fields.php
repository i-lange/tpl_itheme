<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;

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
?>
<?php foreach ($filter->ishop_fields as $field) : ?>
	<?php if ($field->type === 0) : // Числовые значение?>
		<?php
         [$min, $max] = explode(',', $field->values);
         if ($min == $max) {
             continue;
         }
 
         $min = round($min, 0, PHP_ROUND_HALF_DOWN);
         $max = round($max, 0, PHP_ROUND_HALF_UP);
 
 		if (isset($filter->active['fields'][$field->id]['min']) && is_numeric($filter->active['fields'][$field->id]['min'])) {
 			$tmp_min = $filter->active['fields'][$field->id]['min'];
 		} else {
 			$tmp_min = '';
 		}
 
 		if (isset($filter->active['fields'][$field->id]['max']) && is_numeric($filter->active['fields'][$field->id]['max'])) {
 			$tmp_max = $filter->active['fields'][$field->id]['max'];
 		} else {
 			$tmp_max = '';
 		}
 		?>
        <span><?php echo $field->title; ?><?php echo (empty($field->unit)) ? '' : ', ' . $field->unit; ?>:</span>
        <div class="range">
            <div class="range-inputs">
                <div class="input">
                    <input type="number"
                           class="form-control range-min"
                           id="ishop_fields_<?php echo $field->id; ?>_from"
                           min="<?php echo $min ?>"
                           max="<?php echo $max; ?>"
                           name="ishop_fields[<?php echo $field->id; ?>][min]"
                           placeholder="<?php echo $min; ?>"
                           value="<?php echo $tmp_min; ?>">
                    <label class="form-label input__hint" for="ishop_fields_<?php echo $field->id; ?>_from"><?php echo Text::_('MOD_ISHOP_FILTER_BY_PRICE_FROM'); ?></label>
                </div>
                <div class="input">
                    <input type="number"
                           class="form-control range-max"
                           id="ishop_fields_<?php echo $field->id; ?>_to"
                           min="<?php echo $min; ?>"
                           max="<?php echo $max; ?>"
                           name="ishop_fields[<?php echo $field->id; ?>][max]"
                           placeholder="<?php echo $max; ?>"
                           value="<?php echo $tmp_max; ?>">
                    <label class="form-label input__hint" for="ishop_fields_<?php echo $field->id; ?>_to"><?php echo Text::_('MOD_ISHOP_FILTER_BY_PRICE_TO'); ?></label>
                </div>
            </div>
            <div class="range-slider">
                <div class="range-slider__line"></div>
                <div class="range-slider__point range-slider__point--upper"></div>
                <div class="range-slider__point range-slider__point--lower"></div>
            </div>
        </div>
 	<?php elseif ($field->type === 2) : // Да или Нет?>
 		<?php
 		$checked = '';
 		if (isset($filter->active['fields'][$field->id]) && (int) $filter->active['fields'][$field->id] === 1) {
 			$checked = 'checked';
 		}
 		?>
        <div class="nav-link">
            <input type="hidden" name="ishop_fields[<?php echo $field->id; ?>]" value="0">
            <div class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       role="switch"
                       id="ishop_fields_<?php echo $field->id; ?>_bool"
                       name="ishop_fields[<?php echo $field->id; ?>]"
                       value="1" <?php echo $checked; ?>>
                <label class="form-check-label" for="ishop_fields_<?php echo $field->id; ?>_bool"><?php echo $field->title; ?></label>
            </div>
        </div>
 	<?php else : // Строковые из списка?>
 		<?php
        $values = array_combine(explode('||', $field->values_id), explode('||', $field->values));
        // Не нужно выводить, если выбор из одного варианта
 		if (count($values) <= 1) {
 			continue;
 		}
        // Добавляем панель с выбором значений характеристики
        $subPanels[$field->id]['title'] = $field->title;
        $subPanels[$field->id]['alias'] = $field->alias;
        $subPanels[$field->id]['values'] = $values;
 		?>
        <span class="nav-link separator" data-panel-target="off-panel-<?php echo $field->alias; ?>"><?php echo $field->title; ?></span>
 	<?php endif; ?>
<?php endforeach; ?>