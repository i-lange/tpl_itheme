<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

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
<div class="card card-rounded card-shadow">
    <div class="card-body">
    <?php if ($product->price) : ?>
        <div class="product-full__prices">
            <div class="product-full__price <?php echo ($product->discount_size > 0) ? 'sale' : '';?>">
                <?php if ($product->discount_size > 0) : ?>
                    <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-sale']); ?>
                <?php endif; ?>
                <?php echo round($product_price, $round); ?><span class="currency"><?php echo $currency; ?></span>
            </div>
            <?php if ($product->old_price > 0) : ?>
                <div class="product-full__old-price">
                    <del><?php echo round($product->old_price, $round); ?></del> <span class="currency"><?php echo $currency; ?></span>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if (!$user->guest) : ?>
        <div class="mb-2 fw-bold" style="color:var(--green)">Пользователь: <?php echo $user->name; ?></div>
    <?php endif; ?>
    <?php if ($product->available) : ?>
        <div class="row gy-1">
            <div class="col-sm-6">
                <?php echo LayoutHelper::render('itheme.product.addtocart', ['item' => $product, 'class' => 'btn-lg w-100']); ?>
            </div>
            <div class="col-sm-6">
                <?php //echo LayoutHelper::render('itheme.product.buynow', ['item' => $product, 'class' => 'btn-lg w-100']); ?>
                <?php echo LayoutHelper::render('itheme.product.buy1click', ['item' => $product, 'class' => 'btn-lg w-100']); ?>
            </div>
        </div>
    <?php else: ?>
        <div class="product-full__not-available"><?php echo Text::_('COM_ISHOP_PRODUCT_NOT_AVAILABLE'); ?></div>
    <?php endif; ?>
        <div class="text-center mt-3">
            <p class="mb-1 fw-medium"><?php echo Text::_('TPL_ITHEME_BUY_BY_PHONE'); ?></p>
            <?php echo LayoutHelper::render('itheme.phone', ['class' => 'btn btn-lg btn-success','icon' => 'i-phone']); ?>
        </div>
    </div>
</div>

<ul class="list-group mt-3">
<?php foreach ($this->item->parts as $part) : ?>
    <li class="list-group-item prod_part_type_<?php echo $part->prod_label; ?>">
        <?php if (!empty($part->icon)) : ?>
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => $part->icon]); ?>
        <?php endif; ?>
        <a class="text-decoration-none"
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
