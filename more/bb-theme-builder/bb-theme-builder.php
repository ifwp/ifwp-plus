<?php

$tab = new _IFWP_Tab('Beaver Builder', 'Theme Builder');
$tab->on('plugins_loaded', function() use($tab){
    if($tab->is_plugin_active('bb-theme-builder/bb-theme-builder.php')){
        $tab->add_switch([
            'id' => 'in_the_loop',
            'name' => 'Fix in_the_loop?',
            'std' => true,
        ]);
        if($tab->get_option('in_the_loop', true)){
            $tab->on('fl_theme_builder_before_render_content', function(){
                global $wp_query;
            	if(!$wp_query->in_the_loop){
            		$wp_query->in_the_loop = true;
            		add_action('fl_theme_builder_after_render_content', function(){
            			global $wp_query;
            			$wp_query->in_the_loop = false;
            		});
            	}
            });
        }
    }
});
