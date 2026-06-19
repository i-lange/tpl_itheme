<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Utilities\ArrayHelper;

extract($displayData);

// Имя файла с иконками из настроек шаблона
$file = HTMLHelper::cleanImageURL(Factory::getApplication()->getTemplate(true)->params->get('iconsFile'))->url;
$file = '/' . ltrim($file, '/');
$attributes = [
    'aria-hidden' => 'true',
];
// Если указан класс
if (!empty($class)) {
    $attributes['class'] = $class;
}
// Если указаны ширина и высота
if (!empty($width) && !empty($height)) {
    $attributes['width'] = $width;
    $attributes['height'] = $height;
}
?>
<svg <?php echo ArrayHelper::toString($attributes); ?>><use href="<?php echo $file, '#', $icon; ?>"/></svg>