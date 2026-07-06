<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
/** @var $this->item */

$params = $this->item->params;
$canEdit = $this->item->params->get('access-edit');
$info    = $params->get('info_block_position', 0);
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));
$currentDate   = Factory::getDate()->format('Y-m-d H:i:s');
?>
<div class="card card-rounded">
    <div class="ratio ratio-16x9">
    <?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
    </div>
    <div class="card-body">
        <h3 class="card-title"><?php echo $this->item->title; ?></h3>
        <?php if (!empty($this->item->introtext)) : ?>
            <div class="card-text text-body-emphasis"><?php echo $this->item->introtext; ?></div>
        <?php endif; ?>
        <div class="d-flex align-items-center justify-content-between">
            <?php if ($params->get('show_readmore') && $this->item->readmore) :
                if ($params->get('access-view')) :
                    $link = Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
                else :
                    $menu = Factory::getApplication()->getMenu();
                    $active = $menu->getActive();
                    $itemId = $active->id;
                    $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
                    $link->setVar('return', base64_encode(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)));
                endif; ?>
                <?php echo LayoutHelper::render('joomla.content.readmore', ['item' => $this->item, 'params' => $params, 'link' => $link]); ?>
            <?php endif; ?>
            <time class="text-secondary" datetime="<?php echo HTMLHelper::_('date', $this->item->publish_up, 'c'); ?>">
                <?php echo HTMLHelper::_('date', $this->item->publish_up, Text::_('DATE_FORMAT_LC3')); ?>
            </time>
        </div>
    </div>
</div>
