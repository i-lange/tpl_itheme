<?php
/**
 * @package    mod_iform
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/mod_iform
 * @copyright  (C) 2023 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

/**
 * Список доступных переменных
 * @var stdClass $module
 * @var Joomla\CMS\Application\CMSApplicationInterface $app
 * @var Joomla\Input\Input $input
 * @var Joomla\Registry\Registry $params
 * @var stdClass $template
 * @var Joomla\CMS\WebAsset\WebAssetManager $wa
 * @var string $captcha
 */

$wa->useScript('tpl.phone-masker');
$session = Factory::getApplication()->getSession();
$UTM = $session->get('UTM');

$formId = 'i-form-' . $module->id;
?>
<div class="module-consultation">
<?php if ($params->get('show_title')) : ?>
    <h3 class="consultation-title">Бесплатная консультация</h3>
<?php endif; ?>
    <form class=""
          id="<?php echo $formId; ?>"
          action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
          method="post"
          data-iform>
        <fieldset>
            <div class="input-row w-100">
                <label class="position-out" for="phone-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_PHONE_LABEL'); ?></label>
                <input class="form-control w-100 required-phone"
                       id="phone-<?php echo $formId; ?>"
                       type="text"
                       name="phone"
                       placeholder="<?php echo Text::_('MOD_IFORM_MODULE_PHONE_PLACEHOLDER'); ?>"
                       required>
                <button class="btn" type="submit">
                    <span class="position-out"><?php echo Text::_('MOD_IFORM_MODULE_BTN'); ?></span>
                    <svg class="svg"><use href="/icons_v3.svg#phone"/></svg>
                </button>
            </div>
            <?php echo HTMLHelper::_('form.token'); ?>
            <input type="hidden" name="title" value="DOMIS.BY - Заказ звонка">
            <input type="hidden" name="module_id" value="<?php echo $module->id; ?>">
            <input type="hidden" name="goal" value="FEEDBACK">
            <input type="hidden" name="metrika_client_id" value="">
            <input type="hidden" name="google_client_id" value="">
        </fieldset>
	    <?php if ($params->get('show_captcha')) : ?>
            <div <?php echo ($params->get('show_captcha_hidden')) ? ' hidden' : ''; ?>>
			    <?php echo $captcha; ?>
            </div>
	    <?php endif; ?>
        <?php if ($params->get('show_confirm')) : ?>
            <div class="form-check">
                <input class="form-check-input"
                       id="in-confirm-<?php echo $formId; ?>"
                       type="checkbox"
                       name="confirm"
                       checked="checked"
                       required>
                <label class="form-check-label consultation-confirm" for="in-confirm-<?php echo $formId; ?>">
                    <span><?php echo Text::_('TPL_FEEDBACK_I_CONFIRM_PRE'); ?>
                    <a href="/politica"><?php echo Text::_('TPL_FEEDBACK_I_CONFIRM_RULES'); ?></a>
                    <?php echo Text::_('TPL_FEEDBACK_I_CONFIRM_POST'); ?></span>
                </label>
            </div>
        <?php endif; ?>
    </form>
    <div class="form_thank_you text-center">
        <svg class="svg"><use href="/icons_v3.svg#smile-5"/></svg>
        <br><span><?php echo Text::_('TPL_FEEDBACK_THANK'); ?></span>
    </div>
</div>