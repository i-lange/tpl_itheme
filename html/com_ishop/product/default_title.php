<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Ilange\Component\Ishop\Site\Helper\FormatHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
?>
<h1 class="product-full__title mb-3 mb-lg-4"><?php echo $this->item->fullname; ?></span></h1>