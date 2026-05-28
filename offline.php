<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\AuthenticationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */
/** @var string $page_class */
/** @var bool $renderModules */
/** @var string $iconsFile */

require JPATH_THEMES . '/itheme/head.php';

$extraButtons = AuthenticationHelper::getLoginButtons('form-login');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>
<body class="<?php echo $page_class; ?>">
<header id="header">
    <?php echo LayoutHelper::render('itheme.logo.img', ['params' => $this->params]); ?>
</header>
<main id="main">
    <jdoc:include type="message" />
    <form action="<?php echo Route::_('index.php', true); ?>" method="post" id="form-login">
        <fieldset>
            <label for="username"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
            <input name="username" class="form-control" id="username" type="text">
            <label for="password"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
            <input name="password" class="form-control" id="password" type="password">

            <?php foreach ($extraButtons as $button) :
                $dataAttributeKeys = array_filter(array_keys($button), function ($key) {
                    return substr($key, 0, 5) == 'data-';
                });
                ?>
                <div class="mod-login__submit form-group">
                    <button type="button"
                            class="btn btn-secondary w-100 mt-4 <?php echo $button['class'] ?? '' ?>"
                            title="<?php echo Text::_($button['label']) ?>"
                            id="<?php echo $button['id'] ?>"
                    <?php foreach ($dataAttributeKeys as $key) : ?>
                        <?php echo $key ?>="<?php echo $button[$key] ?>"
                    <?php endforeach; ?>
                    <?php if ($button['onclick']) : ?>
                        onclick="<?php echo $button['onclick'] ?>"
                    <?php endif; ?>>
                    <?php if (!empty($button['image'])) : ?>
                        <?php echo $button['image']; ?>
                    <?php elseif (!empty($button['svg'])) : ?>
                        <?php echo $button['svg']; ?>
                    <?php endif; ?>
                    <?php echo Text::_($button['label']) ?>
                    </button>
                </div>
            <?php endforeach; ?>
            <button type="submit" name="Submit" class="btn btn-primary"><?php echo Text::_('JLOGIN'); ?></button>
            <input type="hidden" name="option" value="com_users">
            <input type="hidden" name="task" value="user.login">
            <input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
            <?php echo HTMLHelper::_('form.token'); ?>
        </fieldset>
    </form>
</main>
</body>
</html>