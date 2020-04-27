<?php

$tab = new _IFWP_Tab('Beaver Builder', 'Plugin');
$tab->on('plugins_loaded', function() use($tab){
    if($tab->is_plugin_active('bb-plugin/fl-builder.php')){
        $tab->add_switch([
            'label_description' => 'Context: navigation mega menus. For details, see <a href="https://developer.wordpress.org/themes/functionality/navigation-menus/" target="_blank">Navigation Menus</a>, <a href="https://kb.wpbeaverbuilder.com/article/99-layout-templates-overview#saved-templates" target="_blank">Saved layout templates</a>, <a href="https://kb.wpbeaverbuilder.com/article/139-set-up-a-mega-menu" target="_blank">Set up a Mega Menu</a> and <a href="https://make.wordpress.org/support/user-manual/getting-to-know-wordpress/screen-options/" target="_blank">Screen Options</a>.',
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
        ]);
        if($tab->get_option('disable_column_resizing', false)){
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
            'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/658-disable-inline-editing" target="_blank">Disable inline editing</a>.',
            'id' => 'disable_inline_editing',
            'name' => 'Disable inline editing?',
            'std' => true,
        ]);
        if($tab->get_option('disable_inline_editing', true)){
            $tab->on('fl_inline_editing_enabled', '__return_false');
        }
        $tab->add_switch([
            'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/555-customize-row-resizing-behavior" target="_blank">Customize row resizing behavior</a>.',
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
            'label_description' => 'For details, see <a href="https://kb.wpbeaverbuilder.com/article/554-add-a-color-palette-to-the-beaver-builder-editor" target="_blank">Add a color palette to the Beaver Builder editor</a> and <a href="https://getbootstrap.com/docs/4.4/utilities/colors/" target="_blank">Colors</a>.',
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
