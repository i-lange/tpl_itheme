<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <h1><?php echo $this->params->get('page_heading'); ?></h1>
<?php endif; ?>
<div class="row gy-3 gx-1 gx-sm-3 gx-lg-4 mb-5 mb-5">
    <?php foreach ($this->items as $item) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <?php echo LayoutHelper::render('itheme.warehouse', ['item' => $item]) ?>
        </div>
    <?php endforeach; ?>
</div>
