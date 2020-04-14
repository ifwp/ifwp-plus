<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// Date and Time
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// If $offset_or_tz is an empty string, the output is adjusted with the GMT offset in the WordPress option.
if(!function_exists('ifwp_current_time')){
    function ifwp_current_time($type = 'U', $offset_or_tz = ''){
        if('timestamp' === $type){
            $type = 'U';
        }
        if('mysql' === $type){
            $type = 'Y-m-d H:i:s';
        }
        $timezone = $offset_or_tz ? ifwp_timezone($offset_or_tz) : wp_timezone();
        $datetime = new DateTime('now', $timezone);
        return $datetime->format($type);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_date_convert')){
    function ifwp_date_convert($string = '', $fromtz = '', $totz = '', $format = 'Y-m-d H:i:s'){
        $datetime = date_create($string, ifwp_timezone($fromtz));
        if(false === $datetime){
            return gmdate($format, 0);
        }
        return $datetime->setTimezone(ifwp_timezone($totz))->format($format);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_mysql_date')){
	function ifwp_is_mysql_date($pattern = ''){
        if(preg_match('/^\d{4}-\d{2}-\d{2}\s{1}\d{2}:\d{2}:\d{2}$/', $pattern)){
            return true;
        }
		return false;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_offset_or_tz')){
    function ifwp_offset_or_tz($offset_or_tz = ''){
        // Default GMT offset or timezone string. Must be either a valid offset (-12 to 14) or a valid timezone string.
        if(is_numeric($offset_or_tz)){
            return array(
                'gmt_offset' => $offset_or_tz,
                'timezone_string' => '',
            );
        } else {
            // Map UTC+- timezones to gmt_offsets and set timezone_string to empty.
            if(preg_match('/^UTC[+-]/', $offset_or_tz)){
                return array(
                    'gmt_offset' => intval(preg_replace('/UTC\+?/', '', $offset_or_tz)),
                    'timezone_string' => '',
                );
            } else {
                if(in_array($offset_or_tz, timezone_identifiers_list())){
                    return array(
                        'gmt_offset' => 0,
                        'timezone_string' => $offset_or_tz,
                    );
                } else {
                    return array(
                        'gmt_offset' => 0,
                        'timezone_string' => 'UTC',
                    );
                }
            }
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_timezone')){
    function ifwp_timezone($offset_or_tz = ''){
        return new DateTimeZone(ifwp_timezone_string($offset_or_tz));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_timezone_string')){
    function ifwp_timezone_string($offset_or_tz = ''){
        $offset_or_tz = ifwp_offset_or_tz($offset_or_tz);
        $timezone_string = $offset_or_tz['timezone_string'];
        if($timezone_string){
            return $timezone_string;
        }
        $offset = (float) $offset_or_tz['gmt_offset'];
        $hours = (int) $offset;
        $minutes = ($offset - $hours);
        $sign = ($offset < 0) ? '-' : '+';
        $abs_hour = abs($hours);
        $abs_mins = abs($minutes * 60);
        $tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);
        return $tz_offset;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// HTTP API
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_response')){
	function ifwp_is_response($response = array()){
        if(is_array($response) and isset($response['data'], $response['message'], $response['success'])){
            return true;
        }
		return false;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_success')){
	function ifwp_is_success($code = 0){
        if(is_numeric($code)){
            $code = (int) $code;
    		if($code >= 200 and $code < 300){
    			return true;
    		}
        } elseif(ifwp_is_response($code)){
            return $code['success'];
        }
		return false;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_json_decode_response')){
	function ifwp_maybe_json_decode_response($response = array(), $assoc = false, $depth = 512, $options = 0){
		if(ifwp_is_response($response)){
            if(is_string($response['data']) and preg_match('/^\{\".*\"\:.*\}$/', $response['data'])){
                $data = json_decode($response['data'], $assoc, $depth, $options);
				if(json_last_error() == JSON_ERROR_NONE){
					$response['data'] = $data;
				} else {
					$response['message'] = json_last_error_msg();
					$response['success'] = false;
				}
            }
		}
		return $response;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_merge_response')){
	function ifwp_maybe_merge_response($response = array()){
        if(ifwp_is_response($response)){
            $data = $response['data'];
            if(ifwp_is_response($data)){
                $data = ifwp_maybe_json_decode_response($data, true);
                $response['data'] = $data['data'];
                $response['message'] = $data['message'];
                $response['success'] = $data['success'];
            }
        }
        return $response;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_parse_response')){
	function ifwp_parse_response($response = null){
		if(is_a($response, 'Requests_Exception')){
			return ifwp_response_error('', $response->getMessage());
		} elseif(is_a($response, 'Requests_Response')){
			$response_body = $response->body;
			$response_code = $response->status_code;
			$response_message = get_status_header_desc($response_code);
			if(ifwp_is_success($response_code)){
				return ifwp_response_success($response_body, $response_message);
			} else {
				return ifwp_response_error($response_body, $response_message);
			}
		} elseif(is_wp_error($response)){
			return ifwp_response_error('', $response->get_error_message());
		} else {
			$response_body = wp_remote_retrieve_body($response);
			$response_code = wp_remote_retrieve_response_code($response);
            if($response_body and $response_code){
                $response_message = wp_remote_retrieve_response_message($response);
				if(!$response_message){
					$response_message = get_status_header_desc($response_code);
				}
				if(ifwp_is_success($response_code)){
					return ifwp_response_success($response_body, $response_message);
				} else {
					return ifwp_response_error($response_body, $response_message);
				}
            }
		}
        return ifwp_response_error('', __('Invalid object type.'));
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_delete')){
    function ifwp_remote_delete($url, $args = array()){
        $args['method'] = 'DELETE';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_get')){
    function ifwp_remote_get($url, $args = array()){
        $args['method'] = 'GET';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_head')){
    function ifwp_remote_head($url, $args = array()){
        $args['method'] = 'HEAD';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_options')){
    function ifwp_remote_options($url, $args = array()){
        $args['method'] = 'OPTIONS';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_patch')){
    function ifwp_remote_patch($url, $args = array()){
        $args['method'] = 'PATCH';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_post')){
    function ifwp_remote_post($url, $args = array()){
        $args['method'] = 'POST';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_put')){
    function ifwp_remote_put($url, $args = array()){
        $args['method'] = 'PUT';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_trace')){
    function ifwp_remote_trace($url, $args = array()){
        $args['method'] = 'TRACE';
        $http = _wp_http_get_object();
        return $http->request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_response')){
	function ifwp_response($data = '', $message = '', $success = false){
		if(!$message){
            if($success){
                $message = 'OK';
            } else {
                $message = __('Something went wrong.');
            }
		}
        $response = array(
			'data' => $data,
			'message' => $message,
			'success' => $success,
		);
        $response = ifwp_maybe_json_decode_response($response, true);
        $response = ifwp_maybe_merge_response($response);
		return $response;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_response_error')){
	function ifwp_response_error($data = '', $message = ''){
		return ifwp_response($data, $message, false);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_response_success')){
	function ifwp_response_success($data = '', $message = ''){
		return ifwp_response($data, $message, true);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// Log In and Sign Up
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_authenticate_username')){
	function ifwp_authenticate_username($user = null, $username = ''){
        if($user !== null){
            return $user;
        }
        $user = get_user_by('login', $username);
        if($user){
            return $user;
        }
        return null;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_signon')){
	function ifwp_signon($user_login = '', $remember = false){
        if(is_user_logged_in()){
            return false;
        }
        add_filter('authenticate', 'ifwp_authenticate_username', 10, 2);
		$user = wp_signon(array(
			'user_login' => $user_login,
            'user_password' => '',
            'remember' => $remember,
		));
		remove_filter('authenticate', 'ifwp_authenticate_username', 10, 2);
        if($user instanceof WP_User){
            return true;
        }
		return false;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// Miscellaneous
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urldecode')){
	function ifwp_base64_urldecode($data = '', $strict = false){
		return base64_decode(strtr($data, '-_', '+/'), $strict);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urlencode')){
	function ifwp_base64_urlencode($data = ''){
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_clone_role')){
	function ifwp_clone_role($source_role = '', $destination_role = '', $display_name = ''){
        if($source_role and $destination_role and $display_name){
            $role = get_role($source_role);
            $capabilities = $role->capabilities;
            add_role($destination_role, $display_name, $capabilities);
        }
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_format_function')){
	function ifwp_format_function($function_name = '', $args = array()){
		$str = '';
		if($function_name){
			$str .= '<div style="color: #24831d; font-family: monospace; font-weight: 400;">' . $function_name . '(';
			$function_args = array();
			if($args){
				foreach($args as $arg){
					$arg = shortcode_atts(array(
						'default' => 'null',
						'name' => '',
						'type' => '',
					), $arg);
					if($arg['default'] and $arg['name'] and $arg['type']){
						$function_args[] = '<span style="color: #cd2f23; font-family: monospace; font-style: italic; font-weight: 400;">' . $arg['type'] . '</span> <span style="color: #0f55c8; font-family: monospace; font-weight: 400;">$' . $arg['name'] . '</span> = <span style="color: #000; font-family: monospace; font-weight: 400;">' . $arg['default'] . '</span>';
					}
				}
			}
			if($function_args){
				$str .= ' ' . implode(', ', $function_args) . ' ';
			}
			$str .= ')</div>';
		}
		return $str;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
