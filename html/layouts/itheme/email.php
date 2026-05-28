<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Доступные переменные
 * @var object $params
 * @var string $class
 */

$class = !empty($class) ? 'class="' . $class . '"' : '';

if ($email = $params->get('siteEmail')) : ?>
    <a href="mailto:<?php echo $email; ?>"
       <?php echo $class; ?>
       aria-label="<?php echo Text::_('TPL_ITHEME_EMAIL_ARIA_LABEL'), ': ', $email; ?>">
        <?php echo $email; ?>
    </a>
<?php endif;