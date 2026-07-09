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
<section class="card card-rounded border-0 sticky-md-top" aria-labelledby="contacts-feedback-title" style="background-color: var(--gray-300);">
    <div class="card-body p-4">
    <?php if ($params->get('show_title')) : ?>
        <h3 class="h5 d-flex align-items-center gap-2 mb-2" id="contacts-feedback-title">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-message', 'class' => 'text-primary flex-shrink-0']); ?>
            <span>Обратная связь</span>
        </h3>
    <?php endif; ?>
        <form class=""
              id="<?php echo $formId; ?>"
              action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
              method="post"
              data-iform>
            <fieldset>
                <?php if ($params->get('show_text')) : ?>
                    <p class="text-body-secondary small mb-3">Есть вопросы или предложения? Заполните форму, и мы свяжемся с вами</p>
                <?php endif; ?>
                <div class="mb-2">
                    <label class="form-label small mb-1" for="name-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_NAME_LABEL'); ?></label>
                    <input class="form-control form-control-sm"
                           id="name-<?php echo $formId; ?>"
                           type="text"
                           name="name"
                           value=""
                           autocomplete="on"
                           placeholder="<?php echo Text::_('MOD_IFORM_MODULE_NAME_PLACEHOLDER'); ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label small mb-1" for="phone-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_PHONE_LABEL'); ?> <b>*</b></label>
                    <input class="form-control form-control-sm required-phone"
                           id="phone-<?php echo $formId; ?>"
                           type="text"
                           name="phone"
                           value=""
                           autocomplete="on"
                           placeholder="<?php echo Text::_('MOD_IFORM_MODULE_PHONE_PLACEHOLDER'); ?>"
                           required>
                </div>
                <div class="mb-2">
                    <label class="form-label small mb-1" for="email-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_EMAIL_LABEL'); ?></label>
                    <input class="form-control form-control-sm"
                           id="email-<?php echo $formId; ?>"
                           type="email"
                           name="email"
                           value=""
                           autocomplete="on"
                           placeholder="<?php echo Text::_('MOD_IFORM_MODULE_EMAIL_PLACEHOLDER'); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label small mb-1" for="message-<?php echo $module->id; ?>"><?php echo Text::_('MOD_IFORM_MODULE_MESSAGE_LABEL'); ?> <b>*</b></label>
                    <textarea class="form-control form-control-sm"
                              id="message-<?php echo $module->id; ?>"
                              name="message"
                              rows="5"
                              placeholder="<?php echo Text::_('MOD_IFORM_MODULE_MESSAGE_PLACEHOLDER'); ?>"
                              required></textarea>
                </div>
                <?php echo HTMLHelper::_('form.token'); ?>
                <input type="hidden" name="title" value="Форма обратной связи">
                <input type="hidden" name="module_id" value="<?php echo $module->id; ?>">
                <input type="hidden" name="goal" value="FEEDBACK">
                <input type="hidden" name="metrika_client_id" value="">
                <input type="hidden" name="google_client_id" value="">
                <?php echo LayoutHelper::render('itheme.utm_hidden_fields', ['utm' => $UTM]); ?>
                <?php if ($params->get('show_captcha')) : ?>
                    <div <?php echo ($params->get('show_captcha_hidden')) ? ' hidden' : ''; ?>>
                        <?php echo $captcha; ?>
                    </div>
                <?php endif; ?>
                <?php if ($params->get('show_confirm')) : ?>
                    <div class="form-check small mb-3">
                        <input class="form-check-input"
                               id="in-confirm-<?php echo $formId; ?>"
                               type="checkbox"
                               name="confirm"
                               checked="checked"
                               required>
                        <label class="form-check-label" for="in-confirm-<?php echo $formId; ?>"><?php echo Text::_('TPL_ITHEME_FEEDBACK_I_CONFIRM_PRE'); ?><a class="text-decoration-none" href="/politica">
                                <?php echo Text::_('TPL_ITHEME_FEEDBACK_I_CONFIRM_RULES'); ?></a> <?php echo Text::_('TPL_ITHEME_FEEDBACK_I_CONFIRM_POST'); ?></label>
                    </div>
                <?php endif; ?>
                <?php if ($params->get('show_button')) : ?>
                    <button type="submit" class="btn btn-primary w-100">Отправить сообщение</button>
                <?php endif; ?>
            </fieldset>
        </form>
        <div class="form_thank_you text-center">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-smile', 'class' => 'mega primary']); ?>
            <h3>Спасибо за обращение!</h3>
            <p>Ваше сообщение обрабатывается. <br>Наш менеджер свяжется с вами уточнения информации в самое короткое время</p>
        </div>
    </div>
</section>
