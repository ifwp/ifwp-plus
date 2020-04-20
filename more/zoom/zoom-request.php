<?php

class _IFWP_Zoom_Request {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function delete($args = []){
        return $this->request('delete', $args);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function get($args = []){
        return $this->request('get', $args);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function patch($args = []){
        return $this->request('patch', $args);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function post($args = []){
        return $this->request('post', $args);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function put($args = []){
        return $this->request('put', $args);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function __construct($args = []){
        if($args){
            $this->endpoint = array_shift($args);
            if($args){
                // If a UUID starts with “/” or contains “//”, you mustdouble encode the UUID before making an API request.
                $args = array_map('urlencode', $args);
                $args = array_map('urlencode', $args);
                global $wpdb;
                $this->endpoint = $wpdb->prepare($this->endpoint, $args);
                $this->endpoint = $wpdb->remove_placeholder_escape($this->endpoint);
                $this->endpoint = str_replace("'", '', $this->endpoint);
            }
        }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private $endpoint = '';

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private function generate_jwt(){
        $tab = new _IFWP_Tab('Zoom');
		$iss = apply_filters('ifwp_zoom_api_key', $tab->get_option('zoom_api_key'));
		$key = apply_filters('ifwp_zoom_api_secret', $tab->get_option('zoom_api_secret'));
        if($iss and $key){
            if(!class_exists('\Firebase\JWT\JWT')){
                require_once(IFWP_PLUS_DIR . 'more/zoom/php-jwt/vendor/autoload.php');
            }
			$payload = array(
				'iss' => $iss,
				'exp' => time() + MINUTE_IN_SECONDS, // GMT time
			);
			return \Firebase\JWT\JWT::encode($payload, $key);
		}
		return '';
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private function request($method = '', $body = []){
        if($this->endpoint){
            $url = 'https://api.zoom.us/v2';
            $url = apply_filters('ifwp_zoom_api_url', $url);
            $url = $url . $this->endpoint;
            $response = wp_remote_request($url, [
                'body' => $body,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->generate_jwt(),
                    'Content-Type' => 'application/json',
                ],
                'method' => strtoupper($method),
                'timeout' => 30,
            ]);
            $response = ifwp_parse_response($response);
            $response = ifwp_maybe_json_decode_response($response);
            return $response;
        }
        return ifwp_response_error();
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}
