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

$tab2 = new _IFWP_Checklist('Google Analytics');
$items = [];
$items['Google Analytics'] = $tab->get_option('tracking_id') ? $tab2->dashicon_success() : $tab2->dashicon_error() . ' (<a href="' . $tab->admin_url() . '">Configure</a>)';
if($items){
    $tab2->add_custom_html([
		'std' => '<a class="button" href="https://analytics.google.com" target="_blank">Google Analytics</a>',
	]);
	$tab2->add_custom_html([
        'name' => 'Automatically detected',
		'std' => $tab2->admin_table($items),
	]);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
