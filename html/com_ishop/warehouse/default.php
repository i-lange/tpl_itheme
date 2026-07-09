<?php
/**
 * @package    com_ishop
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/com_ishop
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
?>
<h1><?php echo $this->item->title; ?></h1>
    <div class="warhouse-full mb-3">
        <div class="row gy-4">
            <div class="col-12 col-md-6">
                <?php echo $this->loadTemplate('images'); ?>
            </div>
            <div class="col-12 col-md-6">
                <div class="card card-rounded border-0 bg-light h-100">
                    <div class="card-body p-4">
                        <p class="h1"><?php echo $this->item->address; ?></p>
                        <p class="card-text mb-2">
                            <a class="btn text-decoration-none ps-0"
                               href="tel:+<?php echo preg_replace('/[^0-9]/', '', $this->item->phone); ?>"
                               aria-label="<?php echo Text::_('TPL_ITHEME_PHONE_ARIA_LABEL'), ': ', $this->item->phone; ?>"
                               rel="nofollow">
                                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-phone', 'class' => 'text-primary me-1']); ?>
                                <span><?php echo $this->item->phone; ?></span>
                            </a>
                        </p>
                        <div class="mb-2">
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-clock', 'class' => 'text-primary me-1']); ?>
                            <?php echo $this->item->introtext; ?>
                        </div>
                        <div class="mt-4"><a class="btn btn-primary btn-lg"
                       href="https://yandex.ru/maps/?rtext=~<?php echo $this->item->latitude,',',$this->item->longitude; ?>"
                       target="_blank"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-route', 'class' => 'me-1']); ?>Построить маршрут</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if (!empty($this->item->fulltext)) : ?>
    <div class="my-5"><?php echo $this->item->fulltext; ?></div>
<?php endif; ?>
 <?php if (!empty($this->stock)) : ?>
    <h2 class="mt-5">Товары в наличии</h2>
    <div class="scroll-items-list mb-3 gap-1 gap-md-2" data-drag-scroller data-drag-scroller-interactive>
        <button class="btn btn-tag active" type="button">
            <span class="btn-title">Все товары</span>
        </button>
    <?php foreach ($this->stock as $category) : ?>
        <button class="btn btn-tag" type="button" data-instock-category="<?php echo $category->alias; ?>">
            <span class="btn-title"><?php echo $category->title; ?>&nbsp;(<?php echo $category->count; ?>)</span>
        </button>
    <?php endforeach; ?>
    </div>
    <div class="products__grid mb-5">
    <?php foreach ($this->stock as $category) : ?>
        <?php if (!$category->count) continue; ?>
        <?php foreach ($category->products as $product) : ?>
            <?php echo LayoutHelper::render('itheme.product.small', ['item' => $product, 'params' => $this->params]) ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>