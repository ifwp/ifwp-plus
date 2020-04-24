<?php

$tab = new _IFWP_Tab('Checklist');
$tab->add_custom_html([
    'name' => 'Cloudflare',
    'std' => isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? '<i class="dashicons dashicons-yes" style="color: #0073aa;"></i>' : '<i class="dashicons dashicons-no" style="color: #a00;"></i>',
]);
$tab->add_custom_html([
    'name' => 'Network > IP Geolocation',
    'std' => isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? '<i class="dashicons dashicons-yes" style="color: #0073aa;"></i>' : '<i class="dashicons dashicons-no" style="color: #a00;"></i>',
]);
