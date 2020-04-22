<?php

if(!class_exists('_IFWP_Login')){
    class _IFWP_Login extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function cleanup_user_requests($user = false){
    		$user = $this->get_user($user);
    		$expires = (int) apply_filters('user_request_key_expiration', DAY_IN_SECONDS);
    		$ids = get_posts([
                'author' => $user->ID,
    			'date_query' => [
                    [
                        'before' => $expires . ' seconds ago',
    					'column' => 'post_modified_gmt',
                    ],
                ],
    			'fields' => 'ids',
    			'post_name__in' => ['ifwp-confirm-user-email'],
    			'post_status' => 'request-pending',
    			'post_type' => 'user_request',
    			'posts_per_page' => -1,
            ]);
    		if($ids){
    			foreach($ids as $id){
    				wp_update_post([
                        'ID' => $id,
    					'post_password' => '',
    					'post_status' => 'request-failed',
                    ]);
    			}
    		}
    	}

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function completed_user_requests($user = false){
    		$user = $this->get_user($user);
    		return get_posts([
                'author' => $user->ID,
    			'fields' => 'ids',
    			'post_name__in' => ['ifwp-confirm-user-email'],
    			'post_status' => 'request-completed',
    			'post_type' => 'user_request',
    			'posts_per_page' => -1,
            ]);
    	}

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function create_user_request($user = false, $redirect_to = ''){
    		$user = $this->get_user($user);
    		$this->cleanup_user_requests($user);
    		return wp_create_user_request($user->user_email, 'ifwp-confirm-user-email', [
                'redirect_to' => $redirect_to,
            ]);
    	}

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function fake_confirmation($user = false){
            $user = $this->get_user($user);
    		$incompleted_requests = $this->incompleted_user_requests($user);
    		if($incompleted_requests){
    			$request_id = $incompleted_requests[0];
    		} else {
    			$request_id = $this->create_user_request($user);
    		}
    		if(is_wp_error($request_id)){
    			return $request_id;
    		}
    		update_post_meta($request_id, '_wp_user_request_completed_timestamp', time());
    		return wp_update_post([
                'ID' => $request_id,
    			'post_status' => 'request-completed',
            ]);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function get_user($user = false){
            if(is_a($user, 'WP_User')){
    			return $user;
    		} elseif(is_numeric($user)){
    			return get_user_by('id', $user);
    		} elseif(empty($user)){
    			return wp_get_current_user();
            } else {
                return false;
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function get_user_request($request_id = 0){
            if(function_exists('wp_get_user_request')){
    			return wp_get_user_request($request_id);
    		} elseif(function_exists('wp_get_user_request_data')){
    			return wp_get_user_request_data($request_id);
    		} else {
    			return false;
    		}
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function incompleted_user_requests($user = false){
    		$user = $this->get_user($user);
    		$expires = (int) apply_filters('user_request_key_expiration', DAY_IN_SECONDS);
    		return get_posts([
                'author' => $user->ID,
    			'date_query' => [
                    [
                        'after' => $expires . ' seconds ago',
    					'column' => 'post_modified_gmt',
    					'inclusive' => true,
                    ],
                ],
    			'fields' => 'ids',
    			'post_name__in' => ['ifwp-confirm-user-email'],
    			'post_status' => [
                    'request-pending',
                    'request-confirmed',
                ],
    			'post_type' => 'user_request',
    			'posts_per_page' => -1,
            ]);
    	}

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        protected function tmp($user = false){
    		$user = $this->get_user($user);
    		$message = sprintf($this->get_option('pending'), '<code>' . esc_html($user->user_email) . '</code>');
    		if($this->incompleted_user_requests($user)){
    			return new WP_Error('ifwp_confirm_user_email_pending', $message, 'message');
    		} else {
    			$redirect_to = '';
    			if(!empty($_REQUEST['redirect_to'])){
    				$redirect_to = $_REQUEST['redirect_to'];
    			}
    			$request_id = $this->create_user_request($user, $redirect_to);
    			if(is_wp_error($request_id)){
    				return $request_id;
    			}
    			$result = wp_send_user_request($request_id);
    			if(is_wp_error($result)){
    				return $result;
    			}
    			return new WP_Error('ifwp_confirm_user_email_pending', $message, 'message');
    		}
    	}

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function admin_init(){
            if(!current_user_can($this->get_option('capability'))){
                if(!$this->completed_user_requests()){
                    $tmp = $this->tmp();
                    $this->add_admin_notice($tmp->get_error_message(), 'warning');
                }
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function authenticate($user){
            if(is_a($user, 'WP_User')){
    			if(!user_can($user->ID, $this->get_option('capability'))){
    				if(!$this->completed_user_requests($user)){
    					return $this->tmp($user);
    				}
    			}
    		}
    		return $user;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function shake_error_codes($shake_error_codes){
            $shake_error_codes[] = 'ifwp_confirm_user_email_pending';
    		return $shake_error_codes;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function user_request_action_confirmed($request_id){
            $request = $this->get_user_request($request_id);
    		if(!$request){
    			return new WP_Error('invalid_request', __('Invalid user request.'));
    		}
    		update_post_meta($request_id, '_wp_user_request_completed_timestamp', time());
    		return wp_update_post([
                'ID' => $request_id,
    			'post_status' => 'request-completed',
            ]);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function user_request_action_confirmed_message($message = '', $request_id = 0){
            $request = $this->get_user_request($request_id);
            $redirect_to = '';
            if($request){
                $request_data = $request->request_data;
                if(!empty($request_data['redirect_to'])){
                    $redirect_to = $request_data['redirect_to'];
                }
            }
            $message  = '<p class="success">' . $this->get_option('message') . '</p>';
            return $message;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function user_request_action_description($description, $action_name){
            if($action_name == 'ifwp-confirm-user-email'){
    			$description = $this->get_option('action');
    		}
    		return $description;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function validate_password_reset($errors, $user){
            if(!$errors->has_errors() and isset($_POST['pass1']) and !empty($_POST['pass1'])){
                if(user_can($user->ID, $this->get_option('capability'))){
                    return;
                }
                if($this->completed_user_requests($user)){
                    return;
                }
                $this->fake_confirmation($user);
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
