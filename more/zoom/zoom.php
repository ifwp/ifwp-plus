<?php

$tab = new _IFWP_Tab('Zoom');
$tab->on('admin_footer', function() use($tab){
	if($tab->is_current_screen()){ ?>
		<script>
			function ifwp_toggle_password(){
				if(jQuery('#general_zoom_api_secret').attr('type') == 'text'){
					jQuery('#general_zoom_api_secret').attr('type', 'password');
					jQuery('#ifwp_hide_password').text('Show password');
				} else {
					jQuery('#general_zoom_api_secret').attr('type', 'text');
					jQuery('#ifwp_hide_password').text('Hide password');
				}
			}
			jQuery(function($){
				ifwp_toggle_password();
				$('#ifwp_hide_password').on('click', function(e){
					e.preventDefault();
					ifwp_toggle_password();
				});
			});
		</script><?php
	}
});
$tab->add_field([
    'id' => 'zoom_api_key',
    'name' => 'Zoom API Key',
    'type' => 'text',
]);
$tab->add_field([
    'id' => 'zoom_api_secret',
    'name' => 'Zoom API Secret',
    'type' => 'text',
]);
$tab->add_custom_html([
	'id' => 'hide_password',
	'std' => '<button id="ifwp_hide_password" class="button">Hide password</button>',
]);
