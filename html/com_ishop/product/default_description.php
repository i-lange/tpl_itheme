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

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
?>
<?php if (!empty($this->item->fulltext)) : ?>
    <h3 class="mt-5"><?php echo Text::_('COM_ISHOP_PRODUCT_DESCRIPTIONS'); ?></h3>
    <?php echo $this->item->fulltext; ?>
<?php endif; ?>