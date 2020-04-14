<?php

$tab = new _IFWP_Tab('Beaver Builder', 'Plugin');
$tab->on('plugins_loaded', function(){
    if($tab->is_plugin_active('bb-plugin/fl-builder.php')){
        $tab->add_switch([
            'id' => 'b4_colors',
            'name' => 'Use Bootstrap 4 colors?',
            'std' => true,
        ]);
        if($tab->get_option('b4_colors', true)){
            $tab->filter('fl_builder_color_presets', function($colors){
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
