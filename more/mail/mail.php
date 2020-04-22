<?php

$tab = new _IFWP_Mail('', 'Mail');
$tab->add_switch([
    'id' => 'email_template',
    'name' => 'Use an email template?',
    'std' => false,
]);
$tab->add_field([
    'id' => 'prepend',
    'name' => '— Prepend:',
    'rows' => 3,
    'sanitize_callback' => 'none',
    'std' => '<div style="width: 100%; background-color: #eaeced; padding:50px 0;"><div style="width: 600px; margin: 0 auto; background-color: #fff;"><div style="padding: 50px;"><p style="font-family: \'Helvetica Neue\', sans-serif; margin: 0; color: #808080; line-height: 1.6;">',
    'type' => 'textarea',
    'visible' => array('email_template', true),
]);
$tab->add_field([
    'id' => 'append',
    'name' => '— Append:',
    'rows' => 1,
    'sanitize_callback' => 'none',
    'std' => '</p></div></div></div>',
    'type' => 'textarea',
    'visible' => array('email_template', true),
]);
if($tab->get_option('email_template', false)){
    $tab->on('wp_mail', [$tab, 'wp_mail'], 99);
    $tab->on('wp_mail_content_type', [$tab, 'wp_mail_content_type']);
}
