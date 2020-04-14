<?php

if(!class_exists('_IFWP_Tab')){

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

class _IFWP_Tab {

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function __construct($settings_page = '', $tab = ''){
    $this->settings_page_id = _IFWP_Plus::maybe_add_settings_page($settings_page);
    $this->tab_id = _IFWP_Plus::maybe_add_tab($this->settings_page_id, $tab);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function add_custom_html($args = []){
    $args = wp_parse_args($args, [
        'type' => 'custom_html',
    ]);
    return $this->add_field($atts);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function add_field($args = []){
    if(empty($args['id'])){
        $args['id'] = uniqid();
    }
    //$args['id'] = sanitize_title($args['id']);
    return _IFWP_Plus::maybe_add_field($this->tab_id, $args);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function add_switch($args = []){
    $args = wp_parse_args($args, [
        'on_label' => '<i class="dashicons dashicons-yes"></i>',
        'style' => 'square',
        'type' => 'switch',
    ]);
    return $this->add_field($atts);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function filter($function_to_add, $priority = 10, $accepted_args = 1){
    return call_user_func('add_filter', $function_to_add, $priority, $accepted_args);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function get_option($option = '', $default = false){
    $options = get_option(str_replace('-', '_', $this->settings_page_id));
    if(isset($options[$option])){
        return $options[$option];
    }
    return $default;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function is_current_screen(){
    if(is_admin()){
        $current_screen = get_current_screen();
        if($current_screen){
            if(str_replace('toplevel_page_', '', $current_screen->id) === IFWP_PLUS_SLUG or strpos($current_screen->id, IFWP_PLUS_SLUG . '_page_') === 0){
                return true;
            }
        }
    }
    return false;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function is_plugin_active($plugin = ''){
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    return is_plugin_active($plugin);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public function on($function_to_add, $priority = 10, $accepted_args = 1){
    return call_user_func('add_action', $function_to_add, $priority, $accepted_args);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

private $settings_page_id = '', $tab_id = '';

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
