<?php

$tab = new _IFWP_Checklist('Cloudflare');

$items = [];
$items['Cloudflare'] = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $tab->dashicon_success() : $tab->dashicon_error();
$items['Network > IP Geolocation'] = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? $tab->dashicon_success() : $tab->dashicon_error();
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
$items['PHP > post_max_size <= Network > Maximum Upload Size (100 MB)'] = ($post_max_size <= (100 * MB_IN_BYTES) ? $tab->dashicon_success() : $tab->dashicon_error()) . ' (' . size_format($post_max_size) . ')';
if($items){
	$tab->add_custom_html([
		'std' => '<a class="button" href="https://www.cloudflare.com/" target="_blank">Cloudflare</a>',
	]);
	$tab->add_custom_html([
        'name' => 'Automatically detected',
		'std' => $tab->admin_table($items),
	]);
}

$items = [];
$domain = wp_parse_url(site_url(), PHP_URL_HOST);
$items['SSL/TLS > Encryption Mode'] = 'Full';
$items['SSL/TLS > Edge Certificates <code>*.' . $domain . ', ' . $domain . '</code>'] = 'Active';
$items['SSL/TLS > Always Use HTTPS'] = 'On';
$items['SSL/TLS > Automatic HTTPS Rewrites'] = 'On';
$items['Speed > Auto Minify'] = 'JavaScript, CSS, HTML';
$items['Speed > Rocket Loader™'] = 'On';
$items['Caching > Caching Level'] = 'Standard';
$items['Caching > Browser Cache TTL'] = '>= 8 days';
$items['Page Rules > <code>*' . $domain . '/*wp-login.php*</code>'] = 'Security Level: High';
$items['Page Rules > <code>*' . $domain . '/*wp-admin/*</code>'] = 'Security Level: High, Cache Level: Bypass, Disable Apps, Disable Performance';
$items['Page Rules > <code>*' . $domain . '/*?fl_builder</code>'] = 'Cache Level: Bypass, Disable Apps, Disable Performance';
if($items){
	$tab->add_custom_html([
        'name' => 'Must be manually checked',
		'std' => $tab->admin_table($items, false),
	]);
}
