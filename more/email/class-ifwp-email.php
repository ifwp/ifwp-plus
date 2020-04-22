<?php

if(!class_exists('_IFWP_Email')){
    class _IFWP_Email extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function add_template($html = ''){
            $template = '';
        	if($html){
        		$template = $this->get_option('prepend');
                $template .= $html;
                $template .= $this->get_option('append');
        	}
        	return $template;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wp_mail($atts){
            if(isset($atts['message'])){
                $atts['message'] = $this->add_template($atts['message']);
            }
        	return $atts;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wp_mail_content_type($content_type){
    		return 'text/html';
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
