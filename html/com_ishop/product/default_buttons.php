<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();
$tpl = $app->getTemplate(true);

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
/** @var $currency */
// Сделаем ссылку на товар
$product = $this->item;
$round = (int) $this->params->get('roundPrice', 0);
$currency = strtoupper($this->params->get('defaultCurrency', 'BYN'));
$product_price = ($product->sale_price > 0) ? $product->sale_price : $product->price;
$user = Factory::getApplication()->getIdentity();
?>
<?php if (!empty($this->item->sale_end)) : ?>
    <div class="product-full__sales mb-2">До конца акции: 22 ч. 35 мин.</div>
<?php endif; ?>
<div class="product-full__buttons">
    <div class="product-full__buttons-inner">
    <?php echo LayoutHelper::render('itheme.product.prices', ['item' => $this->item, 'class' => 'mb-3']); ?>
    <?php if (!$user->guest) : ?>
        <div class="mb-2 fw-bold" style="color:var(--green)">Пользователь: <?php echo $user->name; ?></div>
    <?php endif; ?>
    <?php if ($this->item->available) : ?>
        <div class="row g-1">
            <div class="col-12">
                <?php echo LayoutHelper::render('itheme.product.buy1click', ['item' => $this->item, 'class' => 'btn-primary w-100']); ?>
            </div>
            <div class="col-6">
            <?php echo LayoutHelper::render('itheme.product.addtocart', ['item' => $this->item, 'class' => 'btn-outline-primary w-100', 'anchor' => true]); ?>
            </div>
            <?php if ($this->params->get('use_compare', false)) : ?>
            <div class="col-6">
                <button class="btn btn-outline-primary w-100<?php echo ($this->item->incompare) ? ' active' : ''; ?>"
                        title="<?php echo Text::_('TPL_ITHEME_BTN_COMPARE'); ?>"
                        data-tocompare="<?php echo $this->item->id; ?>">
                    <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-compare']); ?><span class="ms-1"><?php echo Text::_('TPL_ITHEME_BTN_COMPARE'); ?></span>
                </button>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($this->item->parts) || (!empty($this->item->delivery_date) && !empty($this->item->delivery))) : ?>
            <ul class="list-group mt-3">
                <?php if (!empty($this->item->delivery_date) && !empty($this->item->delivery)) : ?>
                    <?php $today = new DateTime(); ?>
                    <?php $date = new DateTime($this->item->delivery_date); ?>
                    <?php if ($date >= $today) : ?>
                    <li class="list-group-item small"><span class="text-body-emphasis">Доставим</span> <?php echo $this->item->delivery; ?></li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php foreach ($this->item->parts as $part) : ?>
                    <li class="list-group-item small">
                        <a class="text-body-emphasis text-decoration-none"
                           href="#<?php echo $part->alias; ?>"
                           title="<?php echo Text::_('COM_ISHOP_FIELD_PROD_LABEL_' . $part->prod_label); ?>"><?php echo Text::_('COM_ISHOP_FIELD_PROD_LABEL_' . $part->prod_label); ?></a>
                        <?php if ($part->prod_label_param > 0) : ?>
                            <?php switch ($part->prod_label_param) {
                                case 1:
                                    echo $part->min_payment, ' ', Text::_('COM_ISHOP_PAY_PER_MONTH');
                                    break;
                                case 2:
                                    echo $part->min_rate, '%';
                                    break;
                                case 3:
                                    echo 'до ', $part->max_period, ' ', Text::plural('COM_ISHOP_MONTH', $part->max_period);
                                    break;
                            }
                            ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <div class="btn btn-secondary disabled w-100"><?php echo Text::_('COM_ISHOP_PRODUCT_NOT_AVAILABLE'); ?></div>
        <?php echo LayoutHelper::render('itheme.product.tocatalog', ['item' => $this->item, 'class' => 'btn-outline-primary w-100 mt-2']); ?>
    <?php endif; ?>
    </div>
    <?php if (!empty($this->item->warranty)) : ?>
    <div class="product-full__buttons-bottom"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-shield', 'class' => 'small me-2']); ?><?php echo Text::_('COM_ISHOP_PRODUCT_WARRANTY'); ?> <?php echo $this->item->warranty; ?></div>
    <?php endif; ?>
</div>