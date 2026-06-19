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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

extract($displayData);

/** @var object $item Объект товара */
/** @var string $class Дополнительный класс */

$class = !empty($class) ? ' ' . $class : '';

// Адрес изображения для модального окна
$image = !empty($item->images->image_small)
                ? HTMLHelper::cleanImageURL($item->images->image_small)->url
                : HTMLHelper::cleanImageURL(Factory::getApplication()->getTemplate(true)->params->get('placeholderFile'))->url;

// Структура для модального окна
$json = json_encode([
                        'product_id'   => $item->id,
                        'product_name' => $this->escape($item->fullname),
                        'quantity'     => '1 шт.',
                        'image'        => $image,
                        'price'        => round(($item->sale_price > 0) ? $item->sale_price : $item->price),
                    ]);
?>
<?php if ($item->available) : ?>
    <button class="btn btn-primary <?php echo $class; ?>"
            title="Заказать в 1 клик"
            data-bs-toggle="modal"
            data-bs-target="#buy1clickModal"
            data-1click='<?php echo $json; ?>'>Купить</button>
<?php endif; ?>
