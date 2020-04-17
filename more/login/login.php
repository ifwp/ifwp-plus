<?php

$tab = new _IFWP_Tab('', 'Login');
$tab->add_field([
    'id' => 'logo',
    'max_file_uploads' => 1,
    'max_status' => false,
    'name' => 'Logo:',
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
	$tab->on('login_headertext', function(){
		return get_option('blogname');
	});
	$tab->on('login_headerurl', function(){
		return home_url();
	});
}
