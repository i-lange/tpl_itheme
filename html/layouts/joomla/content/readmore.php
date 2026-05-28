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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

/** @var $displayData */

$params    = $displayData['params'];
$item      = $displayData['item'];
$direction = Factory::getApplication()->getLanguage()->isRtl() ? 'left' : 'right';
?>
<p class="readmore text-end">
    <?php if (!$params->get('access-view')) : ?>
        <a class="btn btn-primary stretched-link" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::_('JGLOBAL_REGISTER_TO_READ_MORE') . ' ' . $this->escape($item->title); ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-'. $direction]); ?>
            <?php echo Text::_('JGLOBAL_REGISTER_TO_READ_MORE'); ?>
        </a>
    <?php elseif ($readmore = $item->alternative_readmore) : ?>
        <a class="btn btn-primary stretched-link" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo $this->escape($readmore . ' ' . $item->title); ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-'. $direction]); ?>
            <?php echo $readmore; ?>
            <?php if ($params->get('show_readmore_title', 0) != 0) : ?>
                <?php echo HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit'), false); ?>
            <?php endif; ?>
        </a>
    <?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
        <a class="btn btn-primary stretched-link" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::sprintf('JGLOBAL_READ_MORE_TITLE', $this->escape($item->title)); ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-'. $direction]); ?>
            <?php echo Text::_('JGLOBAL_READ_MORE'); ?>
        </a>
    <?php else : ?>
        <a class="btn btn-primary stretched-link" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::sprintf('JGLOBAL_READ_MORE_TITLE', $this->escape($item->title)); ?>">
            <?php echo LayoutHelper::render('itheme.icon', ['icon' => 'i-chevron-'. $direction]); ?>
            <?php echo Text::sprintf('JGLOBAL_READ_MORE_TITLE', HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit'), false)); ?>
        </a>
    <?php endif; ?>
</p>
