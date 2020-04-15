<?php

$tab = new _IFWP_Tab('', 'Roles and capabilities');
$tab->add_switch([
    'id' => 'hide_dashboard',
    'name' => 'Hide the dashboard?',
    'std' => false,
]);
$tab->add_text([
    'id' => 'hide_dashboard_capability',
    'name' => '— Minimum capability required to access the dashboard:',
    'std' => 'edit_posts',
    'visible' => array('hide_dashboard', true),
]);
if($tab->get_option('hide_dashboard', false)){
    $tab->on('admin_init', function() use($tab){
        if(wp_doing_ajax()){
            return;
        }
        if(current_user_can($tab->get_option('hide_dashboard_capability', 'edit_posts'))){
            return;
        }
        wp_safe_redirect(home_url());
        exit;
    });
}
