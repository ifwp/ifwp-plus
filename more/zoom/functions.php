<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_generate_jwt')){
	function ifwp_generate_jwt($iss = '', $key = ''){
		if($iss and $key){
			require_once(IFWP_PLUS_DIR . 'more/zoom/php-jwt/vendor/autoload.php');
			$payload = array(
				'iss' => $iss,
				'exp' => time() + MINUTE_IN_SECONDS, // GMT time
			);
			return \Firebase\JWT\JWT::encode($payload, $key);
		}
		return '';
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_zoom_api')){
	function ifwp_zoom_api($api = ''){
		require_once(IFWP_PLUS_DIR . 'more/zoom/zoom-api.php');
		return new IFWP_Plus_Zoom_API($api);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_zoom_request')){
	function ifwp_zoom_request($api = '', $point = '', $arguments = array()){
        $tab = new _IFWP_Tab('Zoom');
		$api_key = apply_filters('zoom_api_key', $tab->get_option('zoom_api_key'));
		$api_secret = apply_filters('zoom_api_secret', $tab->get_option('zoom_api_secret'));
		if($api_key and $api_secret and $api and $point){
			$endpoints = array(
	            'cloud_recording' => array(
	                'list_all_recordings' => array(
	                    'endpoint' => '/users/%s/recordings',
	                    'method' => 'GET',
	                    'parameters' => 1,
	                ),
					'get_meeting_recordings' => array(
	                    'endpoint' => '/meetings/%s/recordings',
	                    'method' => 'GET',
	                    'parameters' => 1,
	                ),
					'delete_meeting_recordings' => array(
	                    'endpoint' => '/meetings/%s/recordings',
	                    'method' => 'DELETE',
	                    'parameters' => 1,
	                ),
					'delete_a_meeting_recording_file' => array(
	                    'endpoint' => '/meetings/%s/recordings/%s',
	                    'method' => 'DELETE',
	                    'parameters' => 2,
	                ),
					'recover_meeting_recordings' => array(
	                    'endpoint' => '/meetings/%s/recordings/status',
	                    'method' => 'PUT',
	                    'parameters' => 1,
	                ),
					'recover_a_single_recording' => array(
	                    'endpoint' => '/meetings/%s/recordings/%s/status',
	                    'method' => 'PUT',
	                    'parameters' => 2,
	                ),
					'get_meeting_recording_settings' => array(
	                    'endpoint' => '/meetings/%s/recordings/settings',
	                    'method' => 'GET',
	                    'parameters' => 1,
	                ),
					'update_meeting_recording_settings' => array(
	                    'endpoint' => '/meetings/%s/recordings/settings',
	                    'method' => 'PATCH',
	                    'parameters' => 1,
	                ),
					'list_recording_registrants' => array(
	                    'endpoint' => '/meetings/%d/recordings/registrants',
	                    'method' => 'GET',
	                    'parameters' => 1,
	                ),
					'create_a_recording_registrant' => array(
	                    'endpoint' => '/meetings/%d/recordings/registrants',
	                    'method' => 'POST',
	                    'parameters' => 1,
	                ),
					'update_recording_registrants_status' => array(
	                    'endpoint' => '/meetings/%d/recordings/registrants/status',
	                    'method' => 'PUT',
	                    'parameters' => 1,
	                ),
					'get_registration_questions' => array(
	                    'endpoint' => '/meetings/%s/recordings/registrants/questions',
	                    'method' => 'GET',
	                    'parameters' => 1,
	                ),
					'update_registration_questions' => array(
	                    'endpoint' => '/meetings/%s/recordings/registrants/questions',
	                    'method' => 'PATCH',
	                    'parameters' => 1,
	                ),
					'list_recordings_of_an_account' => array(
	                    'endpoint' => '/accounts/%s/recordings',
	                    'method' => 'GET',
	                    'parameters' => 1,
	                ),
	            ),
	        );
			$endpoints = apply_filters('ifwp_zoom_endpoints', $endpoints);
	        if(!empty($endpoints[$api][$point])){
				$endpoint = $endpoints[$api][$point]['endpoint'];
		        $method = strtoupper($endpoints[$api][$point]['method']);
		        $parameters = $endpoints[$api][$point]['parameters'];
		        if(count($arguments) >= $parameters){
					if($parameters){
						global $wpdb;
			            $args = array_splice($arguments, 0, $parameters);
						$args = array_map('urlencode', $args);
						$args = array_map('urlencode', $args);
			            $endpoint = $wpdb->prepare($endpoint, $args);
						$endpoint = $wpdb->remove_placeholder_escape($endpoint);
						$endpoint = str_replace("'", '', $endpoint);
			            $offset = count($arguments) - $parameters;
			            $arguments = array_splice($arguments, -$offset);
			        }
					$jwt = ifwp_generate_jwt($api_key, $api_secret);
			        if($jwt){
						$url = 'https://api.zoom.us/v2';
						$url = apply_filters('ifwp_zoom_url', $url);
						$url = $url . $endpoint;
						$response = wp_remote_request($url, array(
							'body' => $arguments,
				            'headers' => array(
				                'Accept' => 'application/json',
				                'Authorization' => 'Bearer ' . $jwt,
				                'Content-Type' => 'application/json',
				            ),
				            'method' => $method,
				            'timeout' => 30,
				        ));
				        $response = ifwp_parse_response($response);
				        $response = ifwp_maybe_json_decode_response($response);
						return $response;
			        }
		        }
	        }
		}
		return ifwp_response_error();
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
