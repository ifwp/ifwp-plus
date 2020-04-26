<?php

$tab = new _IFWP_Login('', 'Login');
$tab->add_field([
    'label_description' => 'Size: medium.',
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
		$logo = wp_get_attachment_image_src($logo_id, 'medium'); ?>
		<style type="text/css">
			#login {
				padding: 20px 0 !important;
			}
			#login h1 a,
			.login h1 a {
				background-image: url(<?php echo $logo[0]; ?>);
				height: <?php echo $logo[2]; ?>px;
				width: <?php echo $logo[1]; ?>px;
				background-size: <?php echo $logo[1]; ?>px <?php echo $logo[2]; ?>px;
			}
		</style><?php
	});
}
$tab->add_switch([
    'id' => 'header_text',
    'name' => 'Local header text?',
    'std' => true,
]);
$header_text = $tab->get_option('header_text', true);
if($header_text){
	$tab->on('login_headertext', function($login_headertext){
        return get_option('blogname');
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
        return home_url();
	});
}
$tab->add_switch([
    'id' => 'remove_username',
    'name' => 'Remove username authentication?',
    'std' => false,
]);
if($tab->get_option('remove_username', false)){
    $tab->on('gettext', [$tab, 'gettext'], 10, 2);
    $tab->off('authenticate', 'wp_authenticate_username_password', 20, 3);
}
$tab->add_switch([
    'id' => 'confirm_user_email',
    'name' => 'Confirm user email?',
    'std' => false,
]);
$tab->add_text([
    'id' => 'capability',
    'name' => '— Minimum capability required to bypass the confirmation:',
    'std' => 'manage_options',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_text([
    'id' => 'action',
    'name' => '— Action name:',
    'std' => 'Confirm your email address',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'id' => 'pending',
    'name' => '— Pending message:',
    'std' => 'Your email address has not been confirmed yet. Please check your inbox at %s for a confirmation email.',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'id' => 'message',
    'name' => '— Confirmation message:',
    'std' => 'Thanks for confirming your email address.',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
if($tab->get_option('confirm_user_email', false)){
    $tab->on('admin_init', [$tab, 'admin_init']);
    $tab->on('authenticate', [$tab, 'authenticate'], 100);
    $tab->on('shake_error_codes', [$tab, 'shake_error_codes']);
    $tab->on('user_request_action_confirmed', [$tab, 'user_request_action_confirmed']);
    $tab->on('user_request_action_confirmed_message', [$tab, 'user_request_action_confirmed_message'], 10, 2);
    $tab->on('user_request_action_description', [$tab, 'user_request_action_description'], 10, 2);
    $tab->on('validate_password_reset', [$tab, 'validate_password_reset'], 10, 2);
}
