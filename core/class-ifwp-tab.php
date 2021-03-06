<?php

if(!class_exists('_IFWP_Tab')){
    class _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($settings_page = '', $tab = ''){
            $this->settings_page_id = _IFWP_Plus::maybe_add_settings_page($settings_page);
            $this->tab_id = _IFWP_Plus::maybe_add_tab($this->settings_page_id, $tab);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_admin_notice($admin_notice = '', $class = 'error', $is_dismissible = false){
            _IFWP_Plus::add_admin_notice($admin_notice, $class, $is_dismissible);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_custom_html($args = []){
            $args = wp_parse_args($args, [
                'type' => 'custom_html',
            ]);
            return $this->add_field($args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_field($args = []){
            if(empty($args['id'])){
                $args['id'] = uniqid();
            }
            if(empty($args['name'])){
                $args['name'] = '';
            }
            $args['id'] = $this->tab_id . '_' . $args['id'];
            return _IFWP_Plus::add_field($this->settings_page_id, $this->tab_id, $args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_switch($args = []){
            $args = wp_parse_args($args, [
                'on_label' => '<i class="dashicons dashicons-yes"></i>',
                'style' => 'square',
                'type' => 'switch',
            ]);
            return $this->add_field($args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_text($args = []){
            $args = wp_parse_args($args, [
                'type' => 'text',
            ]);
            return $this->add_field($args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function admin_url(){
            return admin_url('admin.php?page=' . $this->settings_page_id . '#tab-' . $this->tab_id);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function are_plugins_active($plugins = []){
            $r = false;
            if($plugins){
                $r = true;
                foreach($plugins as $plugin){
                    if(!$this->is_plugin_active($plugin)){
                        $r = false;
                        break;
                    }
                }
            }
            return $r;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function get_option($option = '', $default = false){
            $option = $this->tab_id . '_' . $option;
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
                    if($this->settings_page_id == IFWP_PLUS_SLUG){
                        if($current_screen->id == 'toplevel_page_' . IFWP_PLUS_SLUG){
                            return true;
                        }
                    } else {
                        if($current_screen->id == 'ifwp_page_' . $this->settings_page_id){
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function is_plugin_active($plugin = ''){
            if(!function_exists('is_plugin_active')){
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }
            return is_plugin_active($plugin);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function on($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
            return add_filter($tag, $function_to_add, $priority, $accepted_args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function off($tag = '', $function_to_add = '', $priority = 10, $accepted_args = 1){
            return remove_filter($tag, $function_to_add, $priority, $accepted_args);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected $settings_page_id = '', $tab_id = '';

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
