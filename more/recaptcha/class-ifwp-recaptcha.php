<?php

if(!class_exists('_IFWP_reCAPTCHA')){
    class _IFWP_reCAPTCHA extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function add_shortcode($atts, $content = ''){
    		$html = '<style>.grecaptcha-badge { visibility: hidden; }</style>';
            $html .= '<div id="ifwp-recaptcha-badge">';
            $html .= '<small>';
            $html .= 'This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.';
            $html .= '</small>';
            $html .= '</div>';
            return $html;
    	}

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function init(){
            add_shortcode('ifwp_recaptcha_badge', [$this, 'add_shortcode']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
