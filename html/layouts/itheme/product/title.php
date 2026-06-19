<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

extract($displayData);

/** @var object $item Объект товара */
/** @var bool $mode Как выводить заголовок */

if (!empty($mode)) : ?>
    <h3 class="product-small__title">
        <span class="brand"><?php echo $this->escape($item->manufacturer_title); ?></span><span class="model"> / <?php echo $this->escape($item->title); ?></span>
    </h3>
    <div class="product-small__prefix"><?php echo $this->escape($item->prefix); ?></div>
<?php else : ?>
    <h3 class="product-small__title"><?php echo $this->escape($item->fullname); ?></h3>
<?php endif;