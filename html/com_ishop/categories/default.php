<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>
<?php if ($this->params->get('show_base_description')) : ?>
    <?php // Если в параметрах меню есть описание, используем его ?>
    <?php if ($this->params->get('categories_description')) : ?>
        <div class="category-description">
            <?php echo HTMLHelper::_('content.prepare', $this->params->get('categories_description'), '', $this->get('extension') . '.categories'); ?>
        </div>
    <?php else : ?>
        <?php // Иначе получаем описание из базы данных, если оно существует ?>
        <?php if ($this->parent->description) : ?>
            <div class="category-description">
                <?php echo HTMLHelper::_('content.prepare', $this->parent->description, '', $this->parent->extension . '.categories'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<div class="catalog__grid mb-5">
    <?php echo $this->loadTemplate('items'); ?>
</div>