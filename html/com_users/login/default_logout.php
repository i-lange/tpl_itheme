<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Users\Site\View\Login\HtmlView $this */
?>
<div class="container auth py-5" style="max-width: 32em">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php endif; ?>
    <p>Вы авторизованы как <span class="fw-bold"><?php echo $this->user->name; ?></span>, <br>логин: <?php echo $this->user->username; ?>, <br>email: <?php echo $this->user->email; ?></p>
    <form class=""
          action="<?php echo Route::_('index.php?option=com_users&task=user.logout'); ?>"
          method="post">
        <button type="submit" class="btn btn-primary w-100"><?php echo Text::_('JLOGOUT'); ?></button>
        <?php if ($this->params->get('logout_redirect_url')) : ?>
            <input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_url', $this->form->getValue('return', null, ''))); ?>">
        <?php else : ?>
            <input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logout_redirect_menuitem', $this->form->getValue('return', null, ''))); ?>">
        <?php endif; ?>
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>
