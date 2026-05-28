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

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
?>
<div class="bg-blue py-5">
    <div class="module-part container">
        <h2>Оплата частями</h2>
        <?php foreach ($this->item->parts as $part) : ?>
            <?php $isMulti = count($part->rules) > 1; ?>
            <div class="payment-part" id="<?php echo $part->alias; ?>">
                <div class="payment-part-title">
                    <?php echo LayoutHelper::render('itheme.icon', ['icon' => $part->icon]); ?>
                    <div><span class="payment-part-header"><?php echo $part->title; ?></span><br>
                        <span class="payment-part-desc"><?php echo $part->introtext; ?></span></div>
                </div>
                <div class="scroll-items-list">
                    <?php foreach ($part->rules as $period => $rule) : ?>
                        <div class="card">
                            <div class="card-header"><?php echo $rule->monthly_payment; ?> <?php echo Text::_('COM_ISHOP_PAY_PER_MONTH'); ?></div>
                            <div class="card-body">
                                <div><?php echo Text::_('COM_ISHOP_PART_ON'); ?> <?php echo $period . ' ' . Text::plural('COM_ISHOP_MONTH', $period); ?></div>
                                <div><?php echo Text::_('COM_ISHOP_PART_TOTAL'), ' ', $rule->total_payment, ' ', Text::_('COM_ISHOP_PAY_CURRENCY'); ?></div>
                                <?php if ($part->first_part > 0) : ?>
                                    <div><?php echo Text::_('COM_ISHOP_PART_FIRST_PAY'), ' ', $part->first_part; ?>%</div>
                                <?php else: ?>
                                    <div><?php echo Text::_('COM_ISHOP_PART_FIRST_PAY_NONE'); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
