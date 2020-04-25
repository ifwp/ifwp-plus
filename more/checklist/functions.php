<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_dashtable')){
	function ifwp_dashtable($data = array()){
        $html = '';
        if($data){
            $html .= '<table class="wp-list-table widefat fixed striped">';
        	$html .= '<thead>';
        	$html .= '<tr>';
        	$html .= '<th scope="col">Item</th>';
        	$html .= '<th scope="col">Recommended status</th>';
        	$html .= '</tr>';
        	$html .= '</thead>';
        	$html .= '<tbody>';
        	foreach($data as $key => $value){
        		$html .= '<tr>';
        		$html .= '<td>' . $key . '</td>';
        		$html .= '<td>' . $value . '</td>';
        		$html .= '</tr>';
        	}
        	$html .= '</tbody>';
        	$html .= '</table>';
        }
        return $html;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_dashtable_auto')){
	function ifwp_dashtable_auto($data = array()){
        $html = '';
        if($data){
            $html .= '<table class="wp-list-table widefat fixed striped">';
        	$html .= '<thead>';
        	$html .= '<tr>';
        	$html .= '<th scope="col">Item</th>';
        	$html .= '<th scope="col">Status</th>';
        	$html .= '</tr>';
        	$html .= '</thead>';
        	$html .= '<tbody>';
        	foreach($data as $key => $value){
        		$html .= '<tr>';
        		$html .= '<td>' . $key . '</td>';
        		$html .= '<td>' . $value . '</td>';
        		$html .= '</tr>';
        	}
        	$html .= '</tbody>';
        	$html .= '</table>';
        }
        return $html;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_dashicon_success')){
    function ifwp_dashicon_success(){
        return '<i class="dashicons dashicons-yes" style="color: #46b450;"></i>';
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_dashicon_warning')){
    function ifwp_dashicon_warning(){
        return '<i class="dashicons dashicons-warning" style="color: #ffb900;"></i>';
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_dashicon_error')){
    function ifwp_dashicon_error(){
        return '<i class="dashicons dashicons-no" style="color: #dc3232;"></i>';
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_dashicon_info')){
    function ifwp_dashicon_info(){
        return '<i class="dashicons dashicons-info" style="color: #00a0d2;"></i>';
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
