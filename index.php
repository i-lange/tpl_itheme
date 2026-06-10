<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */
/** @var string $page_class */
/** @var string $stickyHeader */
/** @var string $iconsFile */
/** @var bool $needContainer */

require JPATH_THEMES . '/itheme/head.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
    <script>window.iTheme = window.iTheme || {};</script>
<?php if ($this->params->get('setJivo', false) && ($jivo = $this->params->get('jivoUrl', false))) : ?>
    <script src="<?php echo $jivo; ?>" async></script>
<?php endif; ?>
<?php require JPATH_THEMES . '/itheme/analytics.php'; ?>
	<jdoc:include type="scripts" />
</head>
<body class="<?php echo $page_class; ?>">
<header id="header" class="is-fixed">
    <?php require JPATH_THEMES . '/itheme/header.php'; ?>
</header>
<main id="main">
    <?php if ($this->countModules('banner', true)) : ?>
        <section class="section-banner">
            <jdoc:include type="modules" name="banner" style="none" />
        </section>
    <?php endif; ?>
    <?php if ($this->countModules('top-a', true)) : ?>
        <section class="section-top-a">
            <jdoc:include type="modules" name="top-a" style="none" />
        </section>
    <?php endif; ?>
    <?php if ($this->countModules('top-b', true)) : ?>
        <section class="section-top-b">
            <jdoc:include type="modules" name="top-b" style="none" />
        </section>
    <?php endif; ?>
    <div class="<?php echo ($needContainer) ? 'container ' : ''; ?>section-main">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </div>
    <?php if ($this->countModules('bottom-a', true)) : ?>
        <section class="section-bottom-a">
            <jdoc:include type="modules" name="bottom-a" style="none" />
        </section>
    <?php endif; ?>
    <?php if ($this->countModules('bottom-b', true)) : ?>
        <section class="section-bottom-b">
            <jdoc:include type="modules" name="bottom-b" style="none" />
        </section>
    <?php endif; ?>
</main>
<footer id="footer">
<?php require JPATH_THEMES . '/itheme/footer.php'; ?>
</footer><jdoc:include type="modules" name="debug" style="none" />
</body>
</html>