<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

/** @var string $iconsFile */
?>
<div class="header__top">
    <div class="container">
        <div class="header__top-inner">
            <?php if ($this->countModules('location', true)) : ?>
                <jdoc:include type="modules" name="location" style="none" />
            <?php endif; ?>
            <?php if ($this->countModules('top-menu', true)) : ?>
                <jdoc:include type="modules" name="top-menu" style="none" />
            <?php endif; ?>
            <?php echo LayoutHelper::render('itheme.phone', ['params' => $this->params, 'class' => 'header__phone']); ?>
        </div>
    </div>
</div>
<div class="header__middle">
    <div class="container">
        <div class="header__middle-inner">
            <?php echo LayoutHelper::render('itheme.logo', ['params' => $this->params, 'class' => 'header__logo']); ?>
            <?php if ($this->countModules('search', true)) : ?>
                <jdoc:include type="modules" name="search" style="none" />
            <?php endif; ?>
            <div class="header__actions">
                <?php if ($this->countModules('compare', true)) : ?>
                    <jdoc:include type="modules" name="compare" style="none" />
                <?php endif; ?>
                <?php if ($this->countModules('cart', true)) : ?>
                    <jdoc:include type="modules" name="cart" style="none" />
                <?php endif; ?>
                <?php if ($this->countModules('offcanvas', true)) : ?>
                    <?php echo LayoutHelper::render('itheme.burger'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if ($this->countModules('navbar', true)) : ?>
<nav class="header__bottom">
    <div class="container">
        <jdoc:include type="modules" name="navbar" style="none" />
    </div>
</nav>
<?php endif; ?>
<?php if ($this->countModules('breadcrumbs', true)) : ?>
<div class="header__breadcrumbs">
    <div class="container">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
    </div>
</div>
<?php endif;
