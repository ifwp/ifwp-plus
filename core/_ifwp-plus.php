<?php

if(!class_exists('_IFWP_Plus')){

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

class _IFWP_Plus {

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

public static function add_field($settings_page_id = '', $tab_id = '', $args = []){
    if(empty($args['columns'])){
        $args['columns'] = 12;
    }
	if(array_key_exists($settings_page_id, self::$meta_boxes)){
       if(array_key_exists($tab_id, self::$meta_boxes[$settings_page_id])){
			self::$meta_boxes[$settings_page_id][$tab_id]['fields'][] = $args;
		}
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
                'menu_title' => IFWP_PLUS_NAME,
                'option_name' => str_replace('-', '_', $settings_page_id),
                'page_title' => 'Improvements and Fixes for WordPress',
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
                'page_title' => 'Improvements and Fixes for ' . $settings_page,
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
    $tab_id = sanitize_title($tab);
    if(!array_key_exists($tab_id, self::$tabs[$settings_page_id])){
        self::$tabs[$settings_page_id][$tab_id] = $tab;
        ksort(self::$tabs[$settings_page_id]);
    }
	if(!array_key_exists($settings_page_id, self::$meta_boxes)){
        self::$meta_boxes[$settings_page_id] = [];
    }
    if(!array_key_exists($tab_id, self::$meta_boxes[$settings_page_id])){
        self::$meta_boxes[$settings_page_id][$tab_id] = [
            'fields' => [],
            'id' => $settings_page_id . '-' . $tab_id,
            'settings_pages' => $settings_page_id,
            'tab' => $tab_id,
            'title' => $tab,
        ];
    }
    return $tab_id;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

static public function mb_settings_pages($settings_pages){
    if(self::$settings_pages){
        $general_id = IFWP_PLUS_SLUG;
        if(array_key_exists($general_id, self::$settings_pages)){
            $general = self::$settings_pages[$general_id];
            unset(self::$settings_pages[$general_id]);
            self::$settings_pages = array_merge(array(
                $general_id => $general,
            ), self::$settings_pages);
        }
        foreach(self::$settings_pages as $settings_page_id => $settings_page){
			$empty = true;
			if(array_key_exists($settings_page_id, self::$meta_boxes)){
				foreach(self::$meta_boxes[$settings_page_id] as $meta_box){
					if(!empty($meta_box['fields'])){
						$empty = false;
						break;
					}
				}
			}
			if(!$empty){
				$tabs = self::$tabs[$settings_page_id];
				$general_id = $settings_page_id . '-' . sanitize_title('General');
				if(!empty($tabs[$general_id])){
					$general = $tabs[$general_id];
					unset($tabs[$general_id]);
					$tabs = array_merge(array(
						$general_id => $general,
					), $tabs);
				}
				$settings_page['tabs'] = $tabs;
				$settings_pages[] = $settings_page;
			}
        }
    }
    return $settings_pages;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

static public function rwmb_meta_boxes($meta_boxes){
    if(is_admin()){
        if(self::$meta_boxes){
			foreach(self::$meta_boxes as $tmp){
				if($tmp){
					foreach($tmp as $meta_box){
						if(!empty($meta_box['fields'])){
							$meta_boxes[] = $meta_box;
						}
					}
				}
            }
        }
    }
    return $meta_boxes;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

private static $meta_boxes = [], $settings_pages = [], $tabs = [];

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

add_action('mb_settings_pages', array('_IFWP_Plus', 'mb_settings_pages'));
add_action('rwmb_meta_boxes', array('_IFWP_Plus', 'rwmb_meta_boxes'));

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
