<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\Document\HtmlDocument $this */
/** @var string $page_class */
/** @var string $stickyHeader */
/** @var string $iconsFile */
/** @var bool $needContainer */

require JPATH_THEMES . '/itheme/head.php';

$renderSidebarPosition = static function (string $position): string {
    $modules = ModuleHelper::getModules($position);

    if (empty($modules)) {
        return '';
    }

    $html = '';

    foreach ($modules as $module) {
        $html .= ModuleHelper::renderModule($module, ['style' => 'offcanvas']);
    }

    return trim($html);
};

$sidebarLeft = $renderSidebarPosition('sidebar-left');
$sidebarRight = $renderSidebarPosition('sidebar-right');
$hasSidebarLeft = $sidebarLeft !== '';
$hasSidebarRight = $sidebarRight !== '';
$hasSidebar = $hasSidebarLeft || $hasSidebarRight;
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
    <div class="<?php echo ($needContainer || $hasSidebar) ? 'container ' : ''; ?>section-main">
        <jdoc:include type="message" />
        <div class="template-layout<?php echo $hasSidebarLeft ? ' template-layout--has-left' : ''; ?><?php echo $hasSidebarRight ? ' template-layout--has-right' : ''; ?>">
            <?php if ($hasSidebarLeft) : ?>
                <aside class="template-sidebar template-sidebar--left" aria-label="<?php echo htmlspecialchars(Text::_('TPL_ITHEME_SIDEBAR_LEFT'), ENT_COMPAT, 'UTF-8'); ?>">
                    <?php echo $sidebarLeft; ?>
                </aside>
            <?php endif; ?>
            <div class="template-main">
                <jdoc:include type="modules" name="breadcrumbs" style="none" />
                <jdoc:include type="component" />
            </div>
            <?php if ($hasSidebarRight) : ?>
                <aside class="template-sidebar template-sidebar--right" aria-label="<?php echo htmlspecialchars(Text::_('TPL_ITHEME_SIDEBAR_RIGHT'), ENT_COMPAT, 'UTF-8'); ?>">
                    <?php echo $sidebarRight; ?>
                </aside>
            <?php endif; ?>
        </div>
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
