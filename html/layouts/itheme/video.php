<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
extract($displayData);

/** @var object $video Объект видео */
if (empty($video->video_id) || empty($video->video_source)) {
    return;
}

switch ($video->video_source) {
    case 'rutube':
        $src = 'https://rutube.ru/play/embed/' . $video->video_id;
        $allow = "encrypted-media; fullscreen; picture-in-picture; screen-wake-lock;";
        break;
    case 'vkvideo':
        $ids = explode('_', $video->video_id);
        $src = 'https://vkvideo.ru/video_ext.php?oid=-' . $ids[0] . '&id=' . $ids[1]. '&hd=4';
        $allow = "clipboard-write";
        break;
    default:
        $src = 'https://www.youtube.com/embed/' . $video->video_id;
        $allow = "accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
        break;
}
?>
<iframe width="100%"
        src="<?php echo $src; ?>"
        title="Видео о товаре"
        frameborder="0"
        allow="<?php echo $allow; ?>"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen></iframe>