<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_zoom_request')){
	function ifwp_zoom_request(){
		if(!class_exists('_IFWP_Zoom_Request')){
			require_once(IFWP_PLUS_DIR . 'more/zoom/class-ifwp-zoom-request.php');
		}
		$args = func_get_args();
		return new _IFWP_Zoom_Request($args);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
