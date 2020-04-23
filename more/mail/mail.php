<?php

$tab = new _IFWP_Mail('', 'Mail');
$tab->add_switch([
    'id' => 'email_template',
    'name' => 'Use an email template?',
    'std' => true,
]);
$tab->add_field([
    'id' => 'prepend',
    'name' => '— Prepend:',
    'rows' => 3,
    'sanitize_callback' => 'none',
    'std' => '<div style="width: 100%; background-color: #f3f4f5; padding: 40px 20px;"><div style="margin: 0 auto; width: 600px;"><div style="background-color: #fff; color: #6c757d; font-family: \'Helvetica Neue\', sans-serif; font-size: 16px; line-height: 1.5; margin: 0; padding: 20px 40px;">',
    'type' => 'textarea',
    'visible' => array('email_template', true),
]);
$tab->add_field([
    'id' => 'append',
    'name' => '— Append:',
    'rows' => 1,
    'sanitize_callback' => 'none',
    'std' => '</div></div></div>',
    'type' => 'textarea',
    'visible' => array('email_template', true),
]);
if($tab->get_option('email_template', true)){
    $tab->on('wp_mail', [$tab, 'wp_mail'], 99);
    $tab->on('wp_mail_content_type', [$tab, 'wp_mail_content_type']);
}
