<?php

$tab = new _IFWP_Tab('Beaver Builder', 'Plugin');
$tab->on('plugins_loaded', function() use($tab){
    if($tab->is_plugin_active('bb-plugin/fl-builder.php')){
        $tab->add_switch([
            'id' => 'expand_templates',
            'name' => 'Expand templates?',
            'std' => true,
        ]);
        if($tab->get_option('expand_templates', true)){
            $tab->on('walker_nav_menu_start_el', function($item_output, $item, $depth, $args){
                if($item->object == 'fl-builder-template'){
                    $item_output = $args->before;
                    $item_output .= do_shortcode('[fl_builder_insert_layout id="' . $item->object_id . '"]');
                    $item_output .= $args->after;
                }
                return $item_output;
            }, 10, 4);
        }
        $tab->add_switch([
            'id' => 'disable_column_resizing',
            'name' => 'Disable column resizing?',
            'std' => true,
        ]);
        if($tab->get_option('disable_column_resizing', true)){
            $tab->on('wp_head', function(){
                if(isset($_GET['fl_builder'])){ ?>
                    <style>
                        .fl-block-col-resize {
                            display: none;
                        }
                    </style><?php
                }
            });
        }
        $tab->add_switch([
            'id' => 'disable_inline_editing',
            'name' => 'Disable inline editing?',
            'std' => true,
        ]);
        if($tab->get_option('disable_inline_editing', true)){
            $tab->on('fl_inline_editing_enabled', '__return_false');
        }
        $tab->add_switch([
            'id' => 'disable_row_resizing',
            'name' => 'Disable row resizing?',
            'std' => true,
        ]);
        if($tab->get_option('disable_row_resizing', true)){
            $tab->on('fl_row_resize_settings', function($settings){
                $settings['userCanResizeRows'] = false;
                return $settings;
            });
        }
        $tab->add_switch([
            'id' => 'b4_colors',
            'name' => 'Use Bootstrap 4 colors?',
            'std' => true,
        ]);
        if($tab->get_option('b4_colors', true)){
            $tab->on('fl_builder_color_presets', function($colors){
                $b4_colors = array(
                    '007bff', // primary
                    '6c757d', // secondary
                    '28a745', // success
                    '17a2b8', // info
                    'ffc107', // warning
                    'dc3545', // danger
                    'f8f9fa', // light
                    '343a40', // dark
                );
                return $b4_colors;
            });
        }
    }
});
