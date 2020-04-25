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

$tab2 = new _IFWP_Tab('Checklist', 'Google Analytics');
$items = [];
$items['Google Analytics'] = $tab->get_option('tracking_id') ? ifwp_dashicon_success() : ifwp_dashicon_error() . ' (<a href="' . $tab->admin_url() . '">Configure</a>)';
if($items){
    $tab2->add_custom_html([
		'std' => '<a class="button" href="https://analytics.google.com" target="_blank">Google Analytics</a>',
	]);
	$tab2->add_custom_html([
        'name' => 'Automatically detected',
		'std' => ifwp_dashtable_auto($items),
	]);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
