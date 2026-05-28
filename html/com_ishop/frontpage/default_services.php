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
use Joomla\CMS\Layout\LayoutHelper;

// Имя файла с иконками из настроек шаблона
$iconsFile = HTMLHelper::cleanImageURL(Factory::getApplication()->getTemplate(true)->params->get('iconsFile'))->url;
?>
<?php if (!empty($services = $this->params->get('frontpage_services_list', []))) : ?>
<ul class="advantages__list list-unstyled" role="list">
    <?php foreach ($services as $service) : ?>
        <li class="advantages__item">
            <div class="advantages__icon">
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => $service['icon']]); ?>
            </div>
            <div class="advantages__body">
                <p class="advantages__title"><?php echo $service['title']; ?></p>
                <p class="advantages__text"><?php echo $service['desc']; ?></p>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<?php unset($services); ?>
<?php endif; ?>