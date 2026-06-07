<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */
$this->getDocument()->getWebAssetManager()
    ->useStyle('com_finder.finder')
    ->useScript('com_finder.finder');

if ($this->query->search === true && (int) $this->total > 0) {
    $wa = $this->getDocument()->getWebAssetManager();
    $wa->getRegistry()->addExtensionRegistryFile('com_ishop');
    $wa->useScript('com_ishop.products-loader');
}
?>
<?php if ($this->params->get('show_page_heading')) : ?>
    <h1 class="d-none d-md-block mt-3">
        <?php if ($this->escape($this->params->get('page_heading'))) : ?>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        <?php else : ?>
            <?php echo $this->escape($this->params->get('page_title')); ?>
        <?php endif; ?>
    </h1>
<?php endif; ?>
<?php if ($this->query->search === true) : ?>
    <?php echo $this->loadTemplate('results'); ?>
<?php endif; ?>
