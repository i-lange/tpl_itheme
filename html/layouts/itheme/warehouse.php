<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Ilange\Component\Ishop\Site\Helper\RouteHelper;

extract($displayData);

/** @var object $item Объект магазина */
/** @var object $params Параметры */

// Если не передали параметры, заберем из компонента
if (empty($params)) {
    $params = ComponentHelper::getParams('com_ishop');
}
?>
<div class="card">
    <?php $images = json_decode($item->images); ?>
    <?php $alt = $images->image_small_alt ?: $item->title; ?>
    <?php echo LayoutHelper::render('itheme.image', [
            'class' => 'card-img-top',
            'src' => $images->image_small,
            'alt' => $alt,
            'sizes' => '(max-width: 439px) 100vw, 50vw',
    ]); ?>
    <div class="card-body">
        <h5 class="card-title"><?php echo $item->title; ?></h5>
        <div class="row row-cols-2 gx-1">
            <div>
                <a class="btn btn-primary w-100"
                   href="<?php echo Route::_(RouteHelper::getWarehouseRoute((int)$item->id)); ?>">Подробнее</a>
            </div>
            <div>
                <a class="btn btn-secondary w-100"
                   href="https://yandex.ru/maps/?rtext=~<?php echo $item->latitude,',',$item->longitude; ?>"
                   target="_blank"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-geo', 'class' => 'me-1']); ?>Маршрут</a>
            </div>
        </div>
    </div>
</div>