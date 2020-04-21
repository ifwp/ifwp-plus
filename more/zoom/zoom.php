<?php

$tab = new _IFWP_Zoom('Zoom');
$tab->on('admin_footer', [$tab, 'admin_footer']);
$tab->add_field([
    'id' => 'zoom_api_key',
    'name' => 'Zoom API Key',
    'type' => 'text',
]);
$tab->add_field([
    'id' => 'zoom_api_secret',
    'name' => 'Zoom API Secret',
    'type' => 'text',
]);
$tab->add_custom_html([
	'id' => 'hide_password',
	'std' => '<button id="ifwp_hide_password" class="button">Hide password</button>',
]);
