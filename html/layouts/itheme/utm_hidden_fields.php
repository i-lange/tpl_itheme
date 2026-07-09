<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$utm = $displayData['utm'] ?? [];

if (!is_array($utm)) {
    return;
}

foreach ($utm as $key => $value) {
    if (!is_scalar($key) || !is_scalar($value)) {
        continue;
    }

    $name = (string) $key;
    $value = trim((string) $value);

    if ($value === '' || !preg_match('/^[A-Za-z][A-Za-z0-9_]{0,63}$/', $name)) {
        continue;
    }
    ?>
    <input type="hidden" name="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>">
    <?php
}
