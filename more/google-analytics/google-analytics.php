<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$tab = new _IFWP_Google_Analytics('Google', 'Analytics');
$tab->add_field([
    'id' => 'tracking_id',
    'name' => 'Tracking ID',
    'type' => 'text',
]);
$current_theme = wp_get_theme();
if($current_theme->get('Name') == 'Beaver Builder Theme' or $current_theme->get('Template') == 'bb-theme'){
    $tab->on('fl_head', [$tab, 'head']);
} else {
    $tab->on('wp_head', [$tab, 'head']);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
