<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

$brandText = trim((string) $this->params->get('frontpage_brand_text', ''));
$services  = (array) $this->params->get('frontpage_services_list', []);
$hasBrand  = $brandText !== '';
$hasItems  = !empty($services);
$columnClass = ($hasBrand && $hasItems) ? 'col-12 col-lg-6' : 'col-12';
?>
<div class="advantages row g-3 g-lg-4 align-items-stretch">
<?php if ($hasBrand) : ?>
    <div class="<?php echo $columnClass; ?>">
        <div class="advantages__brand h-100 p-4 p-md-5 bg-light rounded-4 overflow-hidden"><?php echo $brandText; ?></div>
    </div>
<?php endif; ?>
<?php if ($hasItems) : ?>
    <div class="<?php echo $columnClass; ?>">
        <div class="advantages__list row row-cols-1 row-cols-sm-2 g-3 g-lg-4 h-100">
        <?php foreach ($services as $service) : ?>
            <?php
                $service = (array) $service;
                $image   = trim((string) ($service['image'] ?? ''));
                $icon    = trim((string) ($service['icon'] ?? ''));
                $title   = trim((string) ($service['title'] ?? ''));
                $desc    = trim((string) ($service['desc'] ?? ''));
            ?>
            <div class="col">
                <article class="advantages__item h-100 p-4 rounded-4 text-center d-flex flex-column align-items-center justify-content-center">
                    <?php if ($image !== '' || $icon !== '') : ?>
                    <div class="advantages__media" aria-hidden="true">
                        <?php if ($image !== '') : ?>
                            <?php echo LayoutHelper::render('itheme.image', [
                                    'src' => $image,
                                    'alt' => false,
                                    'sizes' => '(max-width: 575px) 100vw, (max-width: 959px) 50vw, 25vw',
                            ]); ?>
                        <?php endif; ?>
                        <?php if ($icon !== '') : ?>
                            <?php echo LayoutHelper::render('itheme.icon', ['icon' => $icon]); ?>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="advantages__body">
                        <?php if ($title !== '') : ?>
                            <h3 class="advantages__title h5 mb-2"><?php echo $this->escape($title); ?></h3>
                        <?php endif; ?>
                        <?php if ($desc !== '') : ?>
                            <p class="advantages__text text-body-emphasis mb-0"><?php echo $this->escape($desc); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
</div>
<?php unset($brandText, $services, $hasBrand, $hasItems, $columnClass); ?>
