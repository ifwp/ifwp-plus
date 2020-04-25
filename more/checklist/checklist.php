<?php

$tab = new _IFWP_Tab('Checklist', 'Cloudflare');

$items = [];
$items['Cloudflare'] = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? ifwp_dashicon_success() : ifwp_dashicon_error();
$items['Network > IP Geolocation'] = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? ifwp_dashicon_success() : ifwp_dashicon_error();
$post_max_size = ini_get('post_max_size');
$post_max_size = strtoupper($post_max_size);
$shorthand_byte_values = array(
	'K' => KB_IN_BYTES,
	'M' => MB_IN_BYTES,
	'G' => GB_IN_BYTES,
);
foreach($shorthand_byte_values as $key => $value){
	if(substr($post_max_size, -1) == $key){
		$post_max_size = intval(substr($post_max_size, 0, -1)) * $value;
		break;
	}
}
$items['PHP > post_max_size <= Network > Maximum Upload Size (100 MB)'] = ($post_max_size <= (100 * MB_IN_BYTES) ? ifwp_dashicon_success() : ifwp_dashicon_error()) . ' (' . size_format($post_max_size) . ')';
if($items){
	$std = '<table class="wp-list-table widefat fixed striped pages">';
	$std .= '<thead>';
	$std .= '<tr>';
	$std .= '<th scope="col">Item</th>';
	$std .= '<th scope="col">Status</th>';
	$std .= '</tr>';
	$std .= '</thead>';
	$std .= '<tbody>';
	foreach($items as $item => $status){
		$std .= '<tr>';
		$std .= '<td>' . $item . '</td>';
		$std .= '<td>' . $status . '</td>';
		$std .= '</tr>';
	}
	$std .= '</tbody>';
	$std .= '</table>';
	$tab->add_custom_html([
        'name' => 'Automatically detected',
		'std' => $std,
	]);
}

$items = [];
$domain = wp_parse_url(site_url(), PHP_URL_HOST);
$items['Page Rules > <code>*' . $domain . '/*wp-login.php*</code>'] = 'Security Level: High';
$items['Page Rules > <code>*' . $domain . '/*wp-admin/*</code>'] = 'Security Level: High, Cache Level: Bypass, Disable Apps, Disable Performance';
$items['Page Rules > <code>*' . $domain . '/*?fl_builder</code>'] = 'Rocket Loader: Off';
if($items){
	$std = '<table class="wp-list-table widefat fixed striped pages">';
	$std .= '<thead>';
	$std .= '<tr>';
	$std .= '<th scope="col">Item</th>';
	$std .= '<th scope="col">Status</th>';
	$std .= '</tr>';
	$std .= '</thead>';
	$std .= '<tbody>';
	foreach($items as $item => $status){
		$std .= '<tr>';
		$std .= '<td>' . $item . '</td>';
		$std .= '<td>' . $status . '</td>';
		$std .= '</tr>';
	}
	$std .= '</tbody>';
	$std .= '</table>';
	$tab->add_custom_html([
        'name' => 'Must be manually checked',
		'std' => $std,
	]);
}
