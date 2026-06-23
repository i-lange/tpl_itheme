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
use Joomla\CMS\Layout\LayoutHelper;

/** @var Ilange\Component\Ishop\Site\View\Product\HtmlView $this */
?>
<?php if (!empty($this->item->parts)) : ?>
    <h2 class="mt-5 mb-4">Оплата частями</h2>
    <div class="row g-4">
    <?php foreach ($this->item->parts as $part) : ?>
        <?php echo LayoutHelper::render('itheme.product.part', ['item' => $part]); ?>
    <?php endforeach; ?>
    </div>
<?php endif;