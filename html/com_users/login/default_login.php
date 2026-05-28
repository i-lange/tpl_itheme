<?php

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Users\Site\View\Login\HtmlView $cookieLogin */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
?>
<div class="container auth py-5" style="max-width: 32em">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php endif; ?>

    <form class=""
          action="<?php echo Route::_('index.php?option=com_users&task=user.login'); ?>"
          method="post">
        <fieldset>
            <?php echo $this->form->renderField('username'); ?>
            <?php echo $this->form->renderField('password'); ?>
            <?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
                <div class="m-2">
                    <div class="form-check">
                        <input class="form-check-input" id="remember" type="checkbox" name="remember" value="yes">
                        <label class="form-check-label" for="remember">
                            <?php echo Text::_('COM_USERS_LOGIN_REMEMBER_ME'); ?>
                        </label>
                    </div>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary w-100"><?php echo Text::_('JLOGIN'); ?></button>
            <?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem', ''))); ?>
            <input type="hidden" name="return" value="<?php echo base64_encode($return); ?>">
            <?php echo HTMLHelper::_('form.token'); ?>
        </fieldset>
    </form>
</div>
