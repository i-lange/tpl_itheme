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

extract($displayData);

/** @var object $item Объект варианта оплаты частями */
/** @var string $class Дополнительный класс */

$class = !empty($class) ? ' ' . $class : '';

// Адрес изображения
$image = !empty($item->images->image_small) ? $item->images->image_small : '';
$alt = !empty($item->images->image_small_alt) ? $item->images->image_small_alt : $item->title;
$count = count($item->rules);
$isMulti = $count > 1;
?>
<div class="col-12 col-md-<?php echo ($isMulti) ? '12' : '6'; ?><?php echo $class; ?>">
    <div class="payment-part<?php echo $class; ?>" id="<?php echo $item->alias; ?>">
        <div class="payment-part-title">
            <?php if (!empty($image)) : ?>
                <?php echo LayoutHelper::render('itheme.image', [
                        'class' => '',
                        'src' => $image,
                        'alt' => $alt,
                        'sizes' => '(max-width: 439px) 100vw, 50vw',
                ]); ?>
            <?php else: ?>
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => $item->icon]); ?>
            <?php endif; ?>
            <div><span class="payment-part-header"><?php echo $item->title; ?></span><br>
                <span class="payment-part-desc"><?php echo $item->introtext; ?></span></div>
        </div>
        <div class="row gy-2 row-cols-1 <?php echo ($isMulti) ? 'row-cols-md-' . $count : ''; ?>">
        <?php foreach ($item->rules as $period => $rule) : ?>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="pb-2 border-bottom"><?php echo $rule->monthly_payment; ?> <?php echo Text::_('COM_ISHOP_PAY_PER_MONTH'); ?></div>
                        <ul class="mb-0">
                            <li><?php echo Text::_('COM_ISHOP_PART_ON'); ?> <?php echo $period . ' ' . Text::plural('COM_ISHOP_MONTH', $period); ?></li>
                            <li><?php echo Text::_('COM_ISHOP_PART_TOTAL'), ' ', $rule->total_payment, ' ', Text::_('COM_ISHOP_PAY_CURRENCY'); ?></li>
                        <?php if ($item->first_part > 0) : ?>
                            <li><?php echo Text::_('COM_ISHOP_PART_FIRST_PAY'), ' ', $item->first_part; ?>%</li>
                        <?php else: ?>
                            <li><?php echo Text::_('COM_ISHOP_PART_FIRST_PAY_NONE'); ?></li>
                        <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>