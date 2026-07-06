<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

use Ilange\Component\Ishop\Site\Helper\RouteHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

/** @var string $sitename */
?>
<div class="footer__main">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-sm-6 col-md-4 col-lg-3 text-center text-sm-start">
                <?php echo LayoutHelper::render('itheme.logo', ['params' => $this->params, 'class' => 'footer__logo']); ?>
                <p class="footer__sitename"><?php echo $sitename; ?></p>
                <?php if ($this->countModules('footer-a', true)) : ?>
                    <jdoc:include type="modules" name="footer-a" style="none"/>
                <?php endif; ?>
                <div class="footer__socials" aria-label="<?php echo Text::_('TPL_ITHEME_SOCIAL_AND_MESSENGERS'); ?>">
                    <?php if ($url = $this->params->get('siteTelegram', false)) : ?>
                        <a class="footer__social-link"
                           href="<?php echo $url; ?>"
                           target="_blank"
                           title="Telegram"
                           aria-label="Telegram"
                           rel="noopener noreferrer"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-telegram']); ?></a>
                    <?php endif; ?>
                    <?php if ($url = $this->params->get('siteViber', false)) : ?>
                        <a class="footer__social-link"
                           href="<?php echo $url; ?>"
                           target="_blank"
                           title="Viber"
                           aria-label="Viber"
                           rel="noopener noreferrer"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-viber']); ?></a>
                    <?php endif; ?>
                    <?php if ($url = $this->params->get('siteVk', false)) : ?>
                        <a class="footer__social-link"
                           href="<?php echo $url; ?>"
                           title="vk.com"
                           aria-label="vk.com"
                           target="_blank"
                           rel="noopener noreferrer"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-vk']); ?></a>
                    <?php endif; ?>
                    <?php if ($url = $this->params->get('siteOk', false)) : ?>
                        <a class="footer__social-link"
                           href="<?php echo $url; ?>"
                           title="ok.ru"
                           aria-label="ok.ru"
                           target="_blank"
                           rel="noopener noreferrer"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-ok']); ?></a>
                    <?php endif; ?>
                    <?php if ($url = $this->params->get('siteInstagram', false)) : ?>
                        <a class="footer__social-link"
                           href="<?php echo $url; ?>"
                           title="Instagram"
                           aria-label="Instagram"
                           target="_blank"
                           rel="noopener noreferrer"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-instagram']); ?></a>
                    <?php endif; ?>
                    <?php if ($url = $this->params->get('siteYouTube', false)) : ?>
                        <a class="footer__social-link"
                           href="<?php echo $url; ?>"
                           title="YouTube"
                           aria-label="YouTube"
                           target="_blank"
                           rel="noopener noreferrer"><?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-youtube']); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-4 col-lg-3 d-none d-md-block">
                <?php if ($this->countModules('footer-b', true)) : ?>
                    <jdoc:include type="modules" name="footer-b" style="html5"/>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <?php if ($this->countModules('footer-c', true)) : ?>
                    <jdoc:include type="modules" name="footer-c" style="html5"/>
                <?php endif; ?>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 d-none d-sm-block">
                <h3><?php echo Text::_('TPL_ITHEME_CONTACTS'); ?></h3>
                <?php if ($this->countModules('footer-d', true)) : ?>
                    <jdoc:include type="modules" name="footer-d" style="none"/>
                <?php endif; ?>
                <?php if ($this->params->get('sitePhone', false)) : ?>
                    <div class="footer__contact">
                        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-phone']); ?>
                        <?php echo LayoutHelper::render('itheme.phone', ['params' => $this->params]); ?>
                    </div>
                <?php endif; ?>
                <?php if ($time = $this->params->get('siteWorkTime', false)) : ?>
                    <div class="footer__contact">
                        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-clock']); ?>
                        <span><?php echo $time; ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($this->params->get('siteEmail', false)) : ?>
                    <div class="footer__contact">
                        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-email']); ?>
                        <?php echo LayoutHelper::render('itheme.email', ['params' => $this->params]); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="footer__bottom">
    <div class="container">
        <p class="footer__copyright">
            <?php echo ($year = $this->params->get('siteFoundation', false)) ? $year . '&nbsp;&ndash;&nbsp;' : ''; ?>
            <?php echo date('Y'); ?>&nbsp;&copy;&nbsp;<?php echo $_SERVER['SERVER_NAME']; ?>. <?php echo $sitename; ?></p>
        <?php if ($this->params->get('siteRights', false)) : ?>
            <p class="footer__legal"><?php echo Text::_('TPL_ITHEME_CONTACTS_RIGHTS_LABEL'); ?></p>
        <?php endif; ?>
        <p class="footer__legal">
            <?php if ($value = $this->params->get('siteRegistration', false)) : ?>
                <?php echo Text::_('TPL_ITHEME_TRADE_REGISTER_WITH'), ' ', $value; ?>.
            <?php endif; ?>
            <?php if ($value = $this->params->get('siteUnp', false)) : ?>
                <?php echo Text::_('TPL_ITHEME_UNP'), ' ', $value; ?>.
            <?php endif; ?>
            <?php if ($this->params->get('siteLegal', false)) : ?>
                <strong><?php echo Text::_('TPL_ITHEME_CONTACTS_LEGAL_LABEL'); ?>.</strong>
            <?php endif; ?>
            <?php if ($this->params->get('siteAddress', false)) : ?>
                <?php echo Text::_('TPL_ITHEME_CONTACTS_ADDRESS_LABEL'); ?>.
            <?php endif; ?>
        </p>
    </div>
    <nav class="module-navigator">
        <div class="navigator-container">
            <div class="navigator-element" data-logo-click>
                <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-home']); ?>
                <span class="visually-hidden"><?php echo Text::_('TPL_NAVIGATOR_HOME'); ?></span>
            </div>
            <div class="navigator-element">
                <a href="<?php echo Route::_(RouteHelper::getCategoriesRoute()); ?>">
                    <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-catalog']); ?>
                    <span class="visually-hidden"><?php echo Text::_('TPL_NAVIGATOR_CATALOG'); ?></span>
                </a>
            </div>
            <?php if ($this->countModules('compare')) : ?>
                <div class="navigator-element">
                    <jdoc:include type="modules" name="compare" style="none"/>
                </div>
            <?php endif; ?>
            <?php if ($this->countModules('cart')) : ?>
                <div class="navigator-element">
                    <jdoc:include type="modules" name="cart" style="none"/>
                </div>
            <?php endif; ?>
            <?php if ($this->params->get('sitePhone')) : ?>
                <div class="navigator-element d-none d-sm-block">
                    <a href="tel:+<?php echo preg_replace('/[^0-9]/', '', $this->params->get('sitePhone')); ?>"
                       rel="noopener noreferrer">
                        <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-phone']); ?>
                        <span class="visually-hidden"><?php echo Text::_('TPL_NAVIGATOR_CALL'); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</div>
<?php if ($this->countModules('offcanvas', true)) : ?>
<?php $this->getWebAssetManager()->useScript('bootstrap.offcanvas'); ?>
<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="mobileMenu"
     aria-labelledby="catalogMenuLabel"
     data-offcanvas-panels>
    <div class="offcanvas-header border-bottom">
        <div class="offcanvas-title-wrap">
            <?php echo LayoutHelper::render('itheme.logo', ['params' => $this->params, 'class' =>'offcanvas-logo', 'alt' => Text::_('TPL_ITHEME_SITENAME')]); ?>
            <h3 class="offcanvas-title" id="catalogMenuLabel"><?php echo Text::_('TPL_ITHEME_MENU'); ?></h3>
        </div>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="<?php echo Text::_('TPL_ITHEME_CLOSE'); ?>"></button>
    </div>
    <div class="offcanvas-body">
        <div class="menu-viewport" data-menu-viewport>
            <jdoc:include type="modules" name="offcanvas" style="offcanvas" />
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($this->countModules('modals', true)) : ?>
    <jdoc:include type="modules" name="modals" style="none" />
<?php endif; ?>
