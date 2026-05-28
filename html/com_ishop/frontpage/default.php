<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
?>
<?php if ($this->params->get('frontpage_show_slider')) : ?>
    <?php echo $this->loadTemplate('slider'); ?>
<?php endif; ?>
<?php if ($this->params->get('frontpage_show_services')) : ?>
<div class="container py-5">
        <?php echo $this->loadTemplate('services'); ?>
</div>
<?php endif; ?>
<?php if ($this->params->get('frontpage_show_categories')) : ?>
<div class="bg-light">
    <div class="container py-5">
        <?php echo $this->loadTemplate('categories'); ?>
    </div>
</div>
<?php endif; ?>
<div class="container py-5">
    <?php if ($this->params->get('frontpage_show_products')) : ?>
        <?php echo $this->loadTemplate('products'); ?>
    <?php endif; ?>
    <?php if ($this->params->get('frontpage_show_article') && !empty($this->text)) : ?>
    <div class="mt-5">
        <?php if ($this->params->get('show_page_heading')) : ?>
            <h1><?php echo $this->params->get('page_heading'); ?></h1>
        <?php endif; ?>
        <?php echo $this->text->introtext . $this->text->fulltext; ?>
    </div>
    <?php endif; ?>
</div>