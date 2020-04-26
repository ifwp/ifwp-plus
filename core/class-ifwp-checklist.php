<?php

if(!class_exists('_IFWP_Checklist')){
    class _IFWP_Checklist extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function __construct($tab = ''){
            parent::__construct('Checklist', $tab);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function admin_table($data = [], $auto = true){
            $html = '';
            if($data){
                $html .= '<table class="wp-list-table widefat fixed striped">';
            	$html .= '<thead>';
            	$html .= '<tr>';
            	$html .= '<th scope="col">Item</th>';
            	$html .= '<th scope="col">' . ($auto ? 'Status' : 'Recommended status') . '</th>';
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

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function dashicon_error(){
            return '<i class="dashicons dashicons-no" style="color: #dc3232;"></i>';
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function dashicon_info(){
            return '<i class="dashicons dashicons-info" style="color: #00a0d2;"></i>';
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function dashicon_success(){
            return '<i class="dashicons dashicons-yes" style="color: #46b450;"></i>';
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function dashicon_warning(){
            return '<i class="dashicons dashicons-warning" style="color: #ffb900;"></i>';
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
