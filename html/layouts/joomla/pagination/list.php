<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var array $displayData */

$list = $displayData['list'];

$count = array_key_last($list['pages']);
$active = 0;
foreach ($list['pages'] as $key => $element) {
    if (!$element['active']) {
        $active = $key;
    }
}

$small_list = [];
if ($active === 1) {
    $small_list[] = $list['pages'][$active];
    if ($count > 1) {
        $small_list[] = $list['pages'][$active + 1];
    }
    if ($count > 2) {
        $small_list[] = $list['pages'][$active + 2];
    }

} elseif ($active === $count) {
    if ($count > 2) {
        $small_list[] = $list['pages'][$active - 2];
    }
    if ($count > 1) {
        $small_list[] = $list['pages'][$active - 1];
    }
    $small_list[] = $list['pages'][$active];

} elseif ($active > 1 || $active < $count) {
    $small_list[] = $list['pages'][$active - 1];
    $small_list[] = $list['pages'][$active];
    $small_list[] = $list['pages'][$active + 1];
}
?>
<nav class="pagination__wrapper mb-3" aria-label="<?php echo Text::_('JLIB_HTML_PAGINATION'); ?>">
    <ul class="pagination justify-content-end ms-0">
        <?php if ($active > 1) : ?>
            <?php echo $list['start']['data']; ?>
            <?php echo $list['previous']['data']; ?>
        <?php endif; ?>

        <?php foreach ($small_list as $page) : ?>
            <?php echo $page['data']; ?>
        <?php endforeach; ?>

        <?php if ($active < $count) : ?>
            <?php echo $list['next']['data']; ?>
            <?php echo $list['end']['data']; ?>
        <?php endif; ?>
    </ul>
</nav>
