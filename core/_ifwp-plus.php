<?php

if(!class_exists('_IFWP_Plus')){

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

class _IFWP_Plus {

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public static function add_field($tab_id = '', $args = array()){
    if(empty($args['columns'])){
        $args['columns'] = 12;
    }
    if(array_key_exists($tab_id, self::$meta_boxes)){
        self::$meta_boxes['fields'][$args['id']] = $args;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public static function maybe_add_settings_page($settings_page = ''){
    if(!$settings_page){
        $settings_page = 'General';
    }
    $settings_page = wp_strip_all_tags($settings_page);
    $settings_page_id = IFWP_PLUS_SLUG;
    if($settings_page != 'General'){
        $settings_page_id .= '-' . sanitize_title($settings_page);
    }
    if(!array_key_exists($settings_page_id, self::$settings_pages)){
        if($settings_page_id == IFWP_PLUS_SLUG){
            self::$settings_pages[$settings_page_id] = [
                'columns' => 1,
                'icon_url' => 'dashicons-plus',
                'id' => $settings_page_id,
                'menu_title' => $settings_page,
                'option_name' => str_replace('-', '_', $settings_page_id),
                'page_title' => 'General Settings',
                'style' => 'no-boxes',
                'submenu_title' => 'General',
                'tabs' => [],
                'tab_style' => 'left',
            ];
        } else {
            self::$settings_pages[$settings_page_id] = [
                'columns' => 1,
                'id' => $settings_page_id,
                'menu_title' => $settings_page,
                'option_name' => str_replace('-', '_', $settings_page_id),
                'page_title' => $settings_page . ' &#8212; Settings',
                'parent' => IFWP_PLUS_SLUG,
                'style' => 'no-boxes',
                'tabs' => [],
                'tab_style' => 'left',
            ];
        }
        ksort(self::$settings_pages);
    }
    return $settings_page_id;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public static function maybe_add_tab($settings_page_id = '', $tab = ''){
    if(!array_key_exists($settings_page_id, self::$tabs)){
        self::$tabs[$settings_page_id] = [];
    }
    if(!$tab){
        $tab = 'General';
    }
    $tab = wp_strip_all_tags($tab);
    $tab_id = $settings_page_id . '-' . sanitize_title($tab);
    if(!array_key_exists($tab_id, self::$tabs[$settings_page_id])){
        self::$tabs[$settings_page_id][$tab_id] = $tab;
        ksort(self::$tabs[$settings_page_id]);
    }
    if(!array_key_exists($tab_id, self::$meta_boxes)){
        self::$meta_boxes[$tab_id] = [
            'fields' => [],
            'id' => $tab_id,
            'settings_pages' => $settings_page_id,
            'tab' => $tab_id,
            'title' => $tab,
        ];
    }
    return $tab_id;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

private static $meta_boxes = [], $settings_pages = [], $tabs = [];

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
