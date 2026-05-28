<?php
/**
 * @package    tpl_itheme
 * @author     Pavel Lange <pavel@ilange.ru>
 * @link       https://github.com/i-lange/tpl_itheme
 * @copyright  (C) 2026 Pavel Lange <https://ilange.ru>
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/** @var Joomla\CMS\Document\HtmlDocument $this */
$wa = $this->getWebAssetManager();
?>
<?php if ($this->params->get('setGoogle', false) && ($googleId = $this->params->get('googleId', false))) : ?>
    <?php
    // Если необходимо подключение счетчика Google Аналитики
    // $googleSrc = '/media/templates/site/itheme/js/google.js';
    $wa->useScript('tpl.google');
    $wa->useScript('tpl.analytics'); ?>
<script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag("js", new Date());
        gtag("config", "<?php echo $googleId; ?>");
        gtag('get', "<?php echo $googleId; ?>", 'client_id', function(client_id) {
            const google_inputs = document.querySelectorAll('input[name="google_client_id"]');
            google_inputs.forEach(function (input) {
                input.value = client_id;
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->params->get('setYandex', false) && ($yandexId = $this->params->get('yandexId', false))) : ?>
    <?php
    // Если необходимо подключение счетчика Яндекс Метрики
    //$yandexSrc = '/media/templates/site/itheme/js/yandex.js';
    $wa->useScript('tpl.yandex');
    $wa->useScript('tpl.analytics'); ?>
<script>
        window.ym = window.ym || function () {
            (window.ym.a = window.ym.a || []).push(arguments);
        };
        window.ym.l = Date.now();
        window.iTheme.metricaId = <?php echo $yandexId; ?>;
        ym(<?php echo $yandexId; ?>, "init", {
            clickmap: true,
            ecommerce: "dataLayer",
            referrer: document.referrer,
            url: location.href,
            accurateTrackBounce: true,
            trackLinks: true
        });
        ym(<?php echo $yandexId; ?>, 'getClientID', function (clientID) {
            const metrika_inputs = document.querySelectorAll('input[name="metrika_client_id"]');
            metrika_inputs.forEach(function (input) {
                input.value = clientID;
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->params->get('setTiktok', false) && ($tiktokId = $this->params->get('tiktokId', false))) : ?>
    <?php
    // Если необходимо подключение пикселя TikTok
    $tiktokSrc = '/media/templates/site/itheme/js/tiktok.js';
    $wa->useScript('tpl.tiktok');
    $wa->useScript('tpl.analytics'); ?>
<script>
    window.TiktokAnalyticsObject = 'ttq';
    window.ttq = window.ttq || [];

    ttq.methods = [
        'page', 'track', 'identify', 'instances', 'debug', 'on', 'off', 'once',
        'ready', 'alias', 'group', 'enableCookie', 'disableCookie',
        'holdConsent', 'revokeConsent', 'grantConsent'
    ];

    ttq.setAndDefer = function (target, method) {
        target[method] = function () {
            target.push([method].concat(Array.prototype.slice.call(arguments, 0)));
        };
    };

    for (var i = 0; i < ttq.methods.length; i++) {
        ttq.setAndDefer(ttq, ttq.methods[i]);
    }

    ttq.instance = function (pixelId) {
        var instance = ttq._i[pixelId] || [];
        for (var i = 0; i < ttq.methods.length; i++) {
            ttq.setAndDefer(instance, ttq.methods[i]);
        }
        return instance;
    };

    ttq._i = ttq._i || {};
    ttq._t = ttq._t || {};
    ttq._o = ttq._o || {};

    ttq.load = function (pixelId, options) {
        ttq._i[pixelId] = ttq._i[pixelId] || [];
        ttq._i[pixelId]._u = '<?php echo $tiktokSrc; ?>';
        ttq._t[pixelId] = +new Date();
        ttq._o[pixelId] = options || {};
    };
    ttq.load('<?php echo $tiktokId; ?>');
    ttq.page();
    </script>
<?php endif; ?>