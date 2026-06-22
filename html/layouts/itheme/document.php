<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;
extract($displayData);

/** @var object $item Объект документа */
if (empty($item->file)) {
    return;
}
$title = $item->name ?? Text::_('TPL_ITHEME_DOC');
$src = '/' . ltrim($item->file, '/');
?>
<a class="btn btn-light w-100 mb-2"
   target="_blank"
   href="<?php echo $src; ?>"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-download', 'class' => 'me-2']); ?><?php echo $title; ?></a>