<?php

if(!class_exists('_IFWP_Zoom')){
    class _IFWP_Zoom extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function admin_footer(){
    		if($this->is_current_screen()){ ?>
    			<script>
    				function ifwp_toggle_password(){
    					var element = jQuery('#<?php echo $this->tab_id; ?>_zoom_api_secret');
    					if(element.attr('type') == 'text'){
    						element.attr('type', 'password');
    						jQuery('#ifwp_hide_password').text('Show password');
    					} else {
    						element.attr('type', 'text');
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
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
