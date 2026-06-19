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

/** @var object $item Объект товара */

if (!empty($item->attribs) || !empty($item->parts)) : ?>
    <div class="product__labels">
        <?php foreach ($item->attribs as $text => $value) : ?>
            <?php if (!empty($value)) : ?>
                <div class="product__label <?php echo $text; ?>">
                    <?php echo Text::_('COM_ISHOP_' . $text); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php foreach ($item->parts as $part) : ?>
            <div class="product__label label_<?php echo $part->cats_label; ?>">
                <?php if (!empty($part->icon)) : ?>
                    <?php echo LayoutHelper::render('itheme.icon', ['icon' => $part->icon]); ?>
                <?php endif; ?>
                <?php if ($part->cats_label_param > 0) : ?>
                    <?php switch ($part->cats_label_param) {
                        case 1:
                            echo Text::sprintf(
                                    'COM_ISHOP_FIELD_CATS_LABEL_' . $part->cats_label,
                                    $part->min_payment);
                            break;
                        case 2:
                            echo Text::sprintf(
                                    'COM_ISHOP_FIELD_CATS_LABEL_' . $part->cats_label,
                                    $part->min_rate . '%');
                            break;
                        case 3:
                            echo Text::sprintf(
                                    'COM_ISHOP_FIELD_CATS_LABEL_' . $part->cats_label,
                                    $part->max_period);
                            break;
                    }
                    ?>
                <?php else: ?>
                    <?php echo Text::_('COM_ISHOP_FIELD_CATS_LABEL_0'); ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;