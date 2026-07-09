<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;

extract($displayData);

/** @var object $item  Объект товара */
/** @var string $class Класс элемента */
/** @var bool   $link  Вывод ссылки на товар */

?>
<div class="product-thumb <?php echo $class; ?>" data-product-id="<?php echo $item->id; ?>">
    <div class="product-thumb__image ratio ratio-3x4">
        <?php echo LayoutHelper::render('itheme.image_product_small', $item); ?>
    </div>
    <div class="product-thumb__title"><?php echo $item->title; ?></div>
    <a class="stretched-link"
       href="<?php echo Route::_(RouteHelper::getProductRoute((int)$item->id, (int)$item->catid)); ?>"
       data-isiteanalytics-select-item
       data-isiteanalytics-product-id="<?php echo (int) $item->id; ?>">
        <span class="visually-hidden"><?php echo $this->escape($item->fullname); ?></span>
    </a>
</div>
