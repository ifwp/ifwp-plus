<?php

if(!class_exists('_IFWP_Mail')){
    class _IFWP_Mail extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private function add_template($html = ''){
            $template = '';
        	if($html){
        		$template = $this->get_option('prepend');
                $template .= wpautop($html);
                $template .= $this->get_option('append');
        	}
        	return $template;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wp_mail($atts){
            if(isset($atts['message'])){
                $pattern = '/<\s*?html\b[^>]*>(.*?)<\/html\b[^>]*>/i';
                if(!preg_match($pattern, $atts['message'])){
                    $atts['message'] = $this->add_template($atts['message']);
                }
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
