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
    <div class="offcanvas-md offcanvas-end" tabindex="-1" id="productDescription" aria-labelledby="productDescriptionLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="productDescriptionLabel"><?php echo Text::_('COM_ISHOP_PRODUCT_DESCRIPTIONS'); ?></h5>
            <button type="button" class="btn-close d-md-none" data-bs-dismiss="offcanvas" data-bs-target="#productDescription" aria-label="Закрыть описание"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <h2 class="d-none d-md-block mt-5"><?php echo Text::_('COM_ISHOP_PRODUCT_DESCRIPTIONS'); ?></h2>
                <?php echo $this->item->fulltext; ?>
            </div>
        </div>
    </div>
<?php endif; ?>