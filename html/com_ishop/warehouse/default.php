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
        <div class="row gy-2">
            <div class="col-12 col-md-5 col-lg-6">
                <?php echo $this->loadTemplate('images'); ?>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-header">Наш адрес</div>
                    <div class="card-body">
                        <p class="card-text"><?php echo $this->item->address; ?></p>
                        <p class="card-title">Телефон</p>
                        <p class="card-text">
                            <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $this->item->phone); ?>"
                           class="text-decoration-none"
                           aria-label="<?php echo Text::_('TPL_ITHEME_PHONE_ARIA_LABEL'), ': ', $this->item->phone; ?>"><?php echo $this->item->phone; ?></a>
                        </p>
                        <p><?php echo $this->item->introtext; ?></p>
                        <a class="btn btn-primary btn-lg"
                       href="https://yandex.ru/maps/?rtext=~<?php echo $this->item->latitude,',',$this->item->longitude; ?>"
                       target="_blank"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-geo', 'class' => 'me-1']); ?>Маршрут</a>
                    </div>
                </div>
            </div>
            <?php if (!empty($this->stock)) : ?>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="card-header">Товары в наличии</div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($this->stock as $category) : ?>
                            <li class="list-group-item">
                                <a class="text-decoration-none"
                                   href="#category-link-<?php echo $category->alias; ?>"><?php echo $category->title; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php if (!empty($this->item->fulltext)) : ?>
    <div class="my-5"><?php echo $this->item->fulltext; ?></div>
<?php endif; ?>
 <?php if (!empty($this->stock)) : ?>
    <?php foreach ($this->stock as $category) : ?>
        <?php if (!$category->count) continue; ?>
        <h2 id="category-link-<?php echo $category->alias; ?>"><?php echo $category->title; ?></h2>
        <div class="products__grid mb-5">
            <?php foreach ($category->products as $product) : ?>
                <?php echo LayoutHelper::render('itheme.product.small', ['item' => $product, 'params' => $this->params]) ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>