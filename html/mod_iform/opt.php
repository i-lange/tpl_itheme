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
use Joomla\CMS\Layout\LayoutHelper;
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
$wa->useScript('tpl.submit');
$session = Factory::getApplication()->getSession();
$UTM = $session->get('UTM');

$formId = 'i-form-' . $module->id;
?>
<div class="bg-light py-5" id="opt-form">
    <div class="container" style="max-width:30rem">
    <?php if ($params->get('show_title')) : ?>
        <h3 class="h2 text-center">Запрос оптового прайса</h3>
    <?php endif; ?>
        <form class=""
              id="<?php echo $formId; ?>"
              action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
              method="post"
              data-iform>
            <fieldset>
                <?php if ($params->get('show_button')) : ?>
                    <div class="mb-2">
                        <label class="form-label" for="name-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_NAME_LABEL'); ?></label>
                        <input class="form-control"
                               id="name-<?php echo $formId; ?>"
                               type="text"
                               name="name"
                               placeholder="<?php echo Text::_('MOD_IFORM_MODULE_NAME_PLACEHOLDER'); ?>"
                               autocomplete="on">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="phone-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_PHONE_LABEL'); ?></label>
                        <input class="form-control w-100 required-phone"
                               id="phone-<?php echo $formId; ?>"
                               type="text"
                               name="phone"
                               value="+375"
                               placeholder="<?php echo Text::_('MOD_IFORM_MODULE_PHONE_PLACEHOLDER'); ?>"
                               required
                               autocomplete="on">
                    </div>
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
                            <label class="form-check-label" for="in-confirm-<?php echo $formId; ?>">
                                <span><?php echo Text::_('TPL_ITHEME_FEEDBACK_I_CONFIRM_PRE'); ?> <a href="/politica"><?php echo Text::_('TPL_ITHEME_FEEDBACK_I_CONFIRM_RULES'); ?></a></span>
                            </label>
                        </div>
	                <?php endif; ?>
                    <div class="text-center mt-2">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-email']); ?>
                            <span><?php echo Text::_('MOD_IFORM_MODULE_BTN'); ?></span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php echo HTMLHelper::_('form.token'); ?>
                <input type="hidden" name="title" value="Magazin-Gefest.BY - Запрос оптового прайса">
                <input type="hidden" name="module_id" value="<?php echo $module->id; ?>">
                <input type="hidden" name="goal" value="FEEDBACK">
                <input type="hidden" name="metrika_client_id" value="">
                <input type="hidden" name="google_client_id" value="">
                <?php echo LayoutHelper::render('itheme.utm_hidden_fields', ['utm' => $UTM]); ?>
            </fieldset>
        </form>
        <div class="form_thank_you text-center">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-smile', 'class' => 'mega primary']); ?>
            <p class="mt-1"><?php echo Text::_('TPL_ITHEME_FEEDBACK_THANK'); ?></p>
        </div>
    </div>
    <?php if ($params->get('show_text')) : ?>
    <div class="blockquote mt-3">
        <p><?php echo Text::_('MOD_IFORM_MODULE_TXT'); ?></p>
    </div>
    <?php endif; ?>
</div>
