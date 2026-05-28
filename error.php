<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;

require JPATH_THEMES . '/itheme/head.php';

/** @var Joomla\CMS\Document\HtmlDocument $this */
/** @var string $page_class */
/** @var bool $renderModules */
/** @var string $iconsFile */

// Получаем код ошибки
$errorCode = $this->error->getCode();
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo $page_class; ?>">
<?php if ($renderModules) : ?>
<header id="header">
    <?php require JPATH_THEMES . '/itheme/header.php'; ?>
</header>
<?php endif; ?>
<main id="main">
    <div class="container">
    <?php if ($renderModules && $this->countModules('error-' . $errorCode)) : ?>
        <jdoc:include type="message" />
        <main>
            <jdoc:include type="modules" name="error-<?php echo $errorCode; ?>" style="none" />
        </main>
    <?php else : ?>
        <h1 class="page-header"><?php echo Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
        <div class="card">
            <div class="card-body">
                <jdoc:include type="message" />
                <main>
                    <p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
                    <p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
                    <ul>
                        <li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_MISTYPED_ADDRESS'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                    </ul>
                    <p><?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
                    <p><a href="<?php echo $this->baseurl; ?>" class="btn btn-secondary">
                        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-home']); ?>
                        <?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
                    </p>
                    <hr>
                    <p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
                    <blockquote>
                        <span class="badge bg-secondary"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                    </blockquote>
                </main>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->debug) : ?>
        <div>
            <?php echo $this->renderBacktrace(); ?>
            <?php // Проверка наличия других исключений ?>
            <?php if ($this->error->getPrevious()) : ?>
                <?php $loop = true; ?>
                <?php
                // Здесь и в цикле используется ссылка на `$this->_error`,
                // поскольку метод `setError()` присваивает этому свойству значения,
                // вызывающие ошибки, что необходимо для корректной работы трассировки.
                // Первое присваивание переменной setError() следует выполнить вне цикла,
                // чтобы цикл не пропускал исключения.
                ?>
                <?php $this->setError($this->_error->getPrevious()); ?>
                <?php while ($loop === true) : ?>
                    <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                    <p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php echo $this->renderBacktrace(); ?>
                    <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                <?php endwhile; ?>
                <?php // Сбросить основной объект ошибки до базового значения ?>
                <?php $this->setError($this->error); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>
</main>
<?php if ($renderModules) : ?>
    <footer id="footer">
        <?php require JPATH_THEMES . '/itheme/footer.php'; ?>
    </footer>
    <jdoc:include type="modules" name="debug" style="none" />
<?php endif; ?>
</body>
</html>