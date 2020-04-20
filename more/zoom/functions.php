<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_zoom_request')){
	function ifwp_zoom_request($args = []){
		if(!class_exists('_IFWP_Zoom_Request')){
			require_once(IFWP_PLUS_DIR . 'more/zoom/zoom-request.php');
		}
		return new _IFWP_Zoom_Request($args);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
