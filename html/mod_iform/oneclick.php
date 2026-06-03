<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
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
$formId = 'i-form-' . $module->id;
?>
<div class="modal fade" id="buy1clickModal" tabindex="-1" aria-labelledby="buy1clickLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="buy1clickLabel">Заказ в 1 клик</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="border rounded me-2">
                        <img class="rounded" id="trg-1click-img" width="40" src="/media/templates/site/itheme/images/phd.svg" alt="">
                    </div>
                    <div>
                        <div class="fw-bold" id="trg-1click-title"></div>
                        <div class="text-primary" id="trg-1click-price"></div>
                    </div>
                </div>
                <form class=""
                      id="<?php echo $formId; ?>"
                      action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>"
                      method="post"
                      data-iform>
                    <div class="form-floating mb-2">
                        <input class="form-control"
                               id="name-<?php echo $formId; ?>"
                               type="text"
                               name="name"
                               value=""
                               autocomplete="on"
                               placeholder="<?php echo Text::_('MOD_IFORM_MODULE_NAME_PLACEHOLDER'); ?>">
                        <label class="" for="name-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_NAME_LABEL'); ?></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control required-phone"
                               id="phone-<?php echo $formId; ?>"
                               type="text"
                               name="phone"
                               value="+375"
                               placeholder="<?php echo Text::_('MOD_IFORM_MODULE_PHONE_PLACEHOLDER'); ?>"
                               autocomplete="on"
                               required>
                        <label class="" for="phone-<?php echo $formId; ?>"><?php echo Text::_('MOD_IFORM_MODULE_PHONE_LABEL'); ?><span class="fw-b red">*</span></label>
                    </div>
                    <?php if ($params->get('show_confirm')) : ?>
                    <div class="form-check mb-2">
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
                        <button type="submit" class="btn btn-primary btn-lg w-100">Заказать</button>
                    <?php endif; ?>
                    <?php echo HTMLHelper::_('form.token'); ?>
                    <input type="hidden" name="products" value=''>
                    <input type="hidden" name="total" value="">
                    <input type="hidden" name="item_id" value="">
                    <input type="hidden" name="title" value="Magazin-Gefest.BY - Заказ в 1 клик">
                    <input type="hidden" name="module_id" value="<?php echo $module->id; ?>">
                    <input type="hidden" name="metrika_client_id" value="">
                    <input type="hidden" name="google_client_id" value="">
                </form>
                <div class="form_thank_you text-center">
                    <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-smile', 'class' => 'mega primary']); ?>
                    <h2 class="">Спасибо за покупку!</h2>
                    <p class="">Ваш заказ обрабатывается. <br>Наш менеджер свяжется с вами для подтверждения и консультации в самое короткое время</p>
                </div>
            </div>
        </div>
    </div>
</div>