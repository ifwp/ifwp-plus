<?php

$tab = new _IFWP_Tab('', 'Login');
$tab->add_switch([
    'id' => 'header_text',
    'name' => 'Local header text?',
    'std' => true,
]);
$header_text = $tab->get_option('header_text', true);
if($header_text){
	$tab->on('login_headertext', function($login_headertext){
        global $pagenow;
        if($pagenow == 'wp-login.php'){
            return get_option('blogname');
        }
        return $login_headertext;
	});
}
$tab->add_switch([
    'id' => 'header_url',
    'name' => 'Local header URL?',
    'std' => true,
]);
$header_url = $tab->get_option('header_url', true);
if($header_url){
	$tab->on('login_headerurl', function($login_headerurl){
        global $pagenow;
        if($pagenow == 'wp-login.php'){
            return home_url();
        }
        return $login_headerurl;
	});
}
$tab->add_field([
    'label_description' => 'Size: thumbnail.',
    'id' => 'logo',
    'max_file_uploads' => 1,
    'max_status' => false,
    'name' => 'Logo',
    'type' => 'image_advanced',
]);
$logo = $tab->get_option('logo', []);
if($logo){
	$logo_id = $logo[0];
	$tab->on('login_enqueue_scripts', function() use($logo_id){
		$logo = wp_get_attachment_image_src($logo_id); ?>
		<style type="text/css">
			#login {
				padding: 20px 0 !important;
			}
			#login h1 a,
			.login h1 a {
				background-image: url(<?php echo $logo[0]; ?>);
				height: 150px;
				width: 150px;
				background-size: 150px;
			}
		</style><?php
	});
}
