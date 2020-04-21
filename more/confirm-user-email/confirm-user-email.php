<?php

$tab = new _IFWP_Confirm_User_Email('', 'Confirm user email');
$tab->add_switch([
    'id' => 'confirm_user_email',
    'name' => 'Confirm user email?',
    'std' => false,
]);
$tab->add_text([
    'id' => 'capability',
    'name' => '— Minimum capability required to bypass the user email confirmation:',
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
    'id' => 'message',
    'name' => '— Confirmation message:',
    'rows' => 1,
    'std' => 'Thanks for confirming your email address.',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'desc' => 'Do not change the placeholders. 1: Email address.',
    'id' => 'pending',
    'name' => '— Pending message:',
    'rows' => 1,
    'std' => 'Your email address has not been confirmed yet. Please check your inbox at %1$s for a confirmation email.',
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
