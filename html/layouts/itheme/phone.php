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

extract($displayData);

/**
 * Доступные переменные
 * @var object $params
 * @var string $class
 * @var string $icon
 */
$app = Factory::getApplication();

// Если не передали параметры, заберем из шаблона
if (empty($params)) {
    $params = $app->getTemplate(true)->params;
}

$class = !empty($class) ? 'class="' . $class . '"' : '';

// Телефон по-умолчанию
$phone = $params->get('sitePhone');
// Подмена номера на определенных страницах сайта
$phone_adds = $params->get('sitePhoneAdditional');
$menu_active = $app->getMenu()->getActive()->id;
foreach ($phone_adds as $phone_add) {
    if ($phone_add->menu == $menu_active) {
        $phone = $phone_add->phone;
        break;
    }
}

if ($phone != '') : ?>
    <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $phone); ?>"
       <?php echo $class; ?>
       aria-label="<?php echo Text::_('TPL_ITHEME_PHONE_ARIA_LABEL'), ': ', $phone; ?>">
        <?php if (!empty($icon)) : ?>
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => $icon]); ?>
        <?php endif; ?>
        <?php echo $phone; ?>
    </a>
<?php endif;