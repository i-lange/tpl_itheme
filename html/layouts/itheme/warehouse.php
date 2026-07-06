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
$images = json_decode($item->images);
$alt = $images->image_small_alt ?: $item->title;
?>
<div class="card card-rounded card-geo">
    <div class="card-body">
        <div class="ratio ratio-16x9">
        <?php echo LayoutHelper::render('itheme.image', [
                'class' => 'object-fit-contain',
                'src' => $images->image_small,
                'alt' => $alt,
                'sizes' => '(max-width: 439px) 100vw, 50vw',
        ]); ?>
        </div>
        <?php if (!empty($item->address)) : ?>
            <h3 class="card-title text-link-color">
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-geo', 'class' => 'me-1']); ?><?php echo $item->address; ?>
            </h3>
        <?php endif; ?>
        <div class="card-text">
        <?php if (!empty($item->phone)) : ?>
            <p class="m-0">
                <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $item->phone); ?>"
                   class="text-decoration-none text-reset"
                   aria-label="<?php echo Text::_('TPL_ITHEME_PHONE_ARIA_LABEL'), ': ', $item->phone; ?>"><?php echo $item->phone; ?></a>
            </p>
        <?php endif; ?>
        <?php if (!empty($item->introtext)) : ?>
            <p class="text-body-emphasis"><?php echo $item->introtext; ?></p>
        <?php endif; ?>
        </div>
        <div class="row gx-1 gy-2 mt-3">
            <div class="col-12 col-lg-6"><a class="btn btn-primary w-100"
               href="https://yandex.ru/maps/?rtext=~<?php echo $item->latitude,',',$item->longitude; ?>"
               target="_blank"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-route', 'class' => 'me-1']); ?>Маршрут</a></div>
            <div class="col-12 col-lg-6"><a class="btn btn-outline-primary w-100"
               href="<?php echo Route::_(RouteHelper::getWarehouseRoute((int)$item->id)); ?>">Подробнее</a></div>
        </div>
    </div>
</div>