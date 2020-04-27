<?php

$tab = new _IFWP_Tab('Beaver Builder', 'Theme');
$current_theme = wp_get_theme();
if($current_theme->get('Name') == 'Beaver Builder Theme' or $current_theme->get('Template') == 'bb-theme'){
    $tab->on('admin_enqueue_scripts', function() use($tab){
        if($tab->is_current_screen()){
            wp_enqueue_script('wp-api');
        }
    });
    $tab->on('admin_footer', function() use($tab){
        if($tab->is_current_screen()){ ?>
            <script>
        		jQuery(function($){
                    $('#ifwp_reboot_default_styles').on('click', function(e){
                        e.preventDefault();
						if(confirm('Are you sure?')){
							$('#ifwp_reboot_default_styles').text('Wait...');
							$.ajax({
								beforeSend: function(xhr){
									xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
								},
								method: 'GET',
								url: wpApiSettings.root + 'ifwp-plus/v1/reboot-default-styles',
							}).done(function(response){
								$('#ifwp_reboot_default_styles').text('Done.');
								setTimeout(function(){
									$('#ifwp_reboot_default_styles').text('Reboot');
								}, 1000);
							});
						}
                    });
        		});
        	</script><?php
        }
    });
    $tab->on('rest_api_init', function(){
        register_rest_route('ifwp-plus/v1', '/reboot-default-styles', array(
            'callback' => function(){
                $mods = get_theme_mods();
        		$mods['fl-scroll-to-top'] = 'enable';
        		$mods['fl-framework'] = 'bootstrap-4';
        		$mods['fl-awesome'] = 'fa5';
                $mods['fl-body-bg-color'] = '#ffffff';
                $mods['fl-accent'] = '#007bff';
                $mods['fl-accent-hover'] = '#007bff';
        		$mods['fl-heading-text-color'] = '#343a40';
        		$mods['fl-heading-font-family'] = 'Open Sans';
                $mods['fl-h1-font-size'] = 40;
                $mods['fl-h1-font-size_medium'] = 33;
                $mods['fl-h1-font-size_mobile'] = 28;
                $mods['fl-h1-line-height'] = 1.2;
                $mods['fl-h1-line-height_medium'] = 1.2;
                $mods['fl-h1-line-height_mobile'] = 1.2;
                $mods['fl-h2-font-size'] = 32;
                $mods['fl-h2-font-size_medium'] = 28;
                $mods['fl-h2-font-size_mobile'] = 24;
                $mods['fl-h2-line-height'] = 1.2;
                $mods['fl-h2-line-height_medium'] = 1.2;
                $mods['fl-h2-line-height_mobile'] = 1.2;
                $mods['fl-h3-font-size'] = 28;
                $mods['fl-h3-font-size_medium'] = 25;
                $mods['fl-h3-font-size_mobile'] = 22;
                $mods['fl-h3-line-height'] = 1.2;
                $mods['fl-h3-line-height_medium'] = 1.2;
                $mods['fl-h3-line-height_mobile'] = 1.2;
                $mods['fl-h4-font-size'] = 24;
                $mods['fl-h4-font-size_medium'] = 22;
                $mods['fl-h4-font-size_mobile'] = 20;
                $mods['fl-h4-line-height'] = 1.2;
                $mods['fl-h4-line-height_medium'] = 1.2;
                $mods['fl-h4-line-height_mobile'] = 1.2;
                $mods['fl-h5-font-size'] = 20;
                $mods['fl-h5-font-size_medium'] = 19;
                $mods['fl-h5-font-size_mobile'] = 16;
                $mods['fl-h5-line-height'] = 1.2;
                $mods['fl-h5-line-height_medium'] = 1.2;
                $mods['fl-h5-line-height_mobile'] = 1.2;
                $mods['fl-h6-font-size'] = 16;
                $mods['fl-h6-font-size_medium'] = 16;
                $mods['fl-h6-font-size_mobile'] = 16;
                $mods['fl-h6-line-height'] = 1.2;
                $mods['fl-h6-line-height_medium'] = 1.2;
                $mods['fl-h6-line-height_mobile'] = 1.2;
                $mods['fl-body-text-color'] = '#6c757d';
                $mods['fl-body-font-family'] = 'Open Sans';
                $mods['fl-body-font-size'] = 16;
                $mods['fl-body-font-size_medium'] = 16;
                $mods['fl-body-font-size_mobile'] = 16;
                $mods['fl-body-line-height'] = 1.5;
                $mods['fl-body-line-height_medium'] = 1.5;
                $mods['fl-body-line-height_mobile'] = 1.5;
                update_option('theme_mods_' . get_option('stylesheet'), $mods);
        		return ifwp_response_success($mods);
            },
            'methods' => 'GET',
            'permission_callback' => function(){
                return current_user_can('manage_options');
            },
        ));
    });
    $tab->add_custom_html([
        'id' => 'reboot_default_styles',
        'name' => 'Reboot default styles?',
        'std' => '<button id="ifwp_reboot_default_styles" class="button">Reboot</button>',
    ]);
    $tab->add_switch([
        'label_description' => 'You must <a href="' . admin_url('options-general.php?page=fl-builder-settings#tools') . '">clear cache</a> for new settings to take effect.',
        'id' => 'remove_default_styles',
        'name' => 'Remove default styles?',
        'std' => true,
    ]);
    if($tab->get_option('remove_default_styles', true)){
        $tab->on('fl_theme_compile_less_paths', function($paths){
            foreach($paths as $index => $path){
                if($path == FL_THEME_DIR . '/less/theme.less'){
                    $paths[$index] = IFWP_PLUS_DIR . 'more/bb-theme/theme.less';
                }
            }
            return $paths;
        });
    }
    $tab->add_switch([
        'id' => 'remove_presets',
        'name' => 'Remove presets?',
        'std' => true,
    ]);
    if($tab->get_option('remove_presets', true)){
        $tab->on('customize_register', function($wp_customize){
            $wp_customize->remove_section('fl-presets');
        }, 20);
    }
    $tab->add_switch([
        'id' => 'b4_colors',
        'name' => 'Use Bootstrap 4 colors?',
        'std' => true,
    ]);
    if($tab->get_option('b4_colors', true)){
        $tab->on('customize_controls_print_footer_scripts', function(){ ?>
            <script>
                var b4_colors = [
                    '#007bff', // primary
                    '#6c757d', // secondary
                    '#28a745', // success
                    '#17a2b8', // info
                    '#ffc107', // warning
                    '#dc3545', // danger
                    '#f8f9fa', // light
                    '#343a40', // dark
                ];
                jQuery(function($){
                    $.wp.wpColorPicker.prototype.options = {
                        palettes: b4_colors,
                    };
                });
            </script><?php
        });
    }
}
