<?php

class _IFWP_Confirm_User_Email extends _IFWP_Tab {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //
    // Private
    //
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private function cleanup_user_requests($user = false){
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

    private function completed_user_requests($user = false){
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

    private function create_user_request($user = false, $redirect_to = ''){
		$user = $this->get_user($user);
		$this->cleanup_user_requests($user);
		return wp_create_user_request($user->user_email, 'ifwp-confirm-user-email', [
            'redirect_to' => $redirect_to,
        ]);
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private function fake_confirmation($user = false){
        $user = $this->get_user($user);
		$request_id = $this->create_user_request($user);
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

    private function get_user($user = false){
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

    private function get_user_request($request_id = 0){
        if(function_exists('wp_get_user_request')){
			return wp_get_user_request($request_id);
		} elseif(function_exists('wp_get_user_request_data')){
			return wp_get_user_request_data($request_id);
		} else {
			return false;
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private function incompleted_user_requests($user = false){
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

    private function tmp($user = false){
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
    //
    // Public
    //
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function admin_init(){
        if(!current_user_can($this->get_option('capability'))){
            if(!$this->completed_user_requests()){
                $tmp = $this->tmp();
                $this->add_admin_notice($tmp->get_error_message(), 'warning');
            }
        }
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function authenticate($user){
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

    function shake_error_codes($shake_error_codes){
        $shake_error_codes[] = 'ifwp_confirm_user_email_pending';
		return $shake_error_codes;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function user_request_action_confirmed($request_id){
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

    function user_request_action_confirmed_message($message = '', $request_id = 0){
        $request = $this->get_user_request($request_id);
        $redirect_to = '';
        if($request){
            $request_data = $request->request_data;
            if(!empty($request_data['redirect_to'])){
                $redirect_to = $request_data['redirect_to'];
            }
        }
        $message  = '<p class="success">' . $tab->get_option('message') . '</p>';
        return $message;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function user_request_action_description($description, $action_name){
        if($action_name == 'ifwp-confirm-user-email'){
			$description = $this->get_option('action');
		}
		return $description;
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function user_request_action_email_content($email_text, $email_data){
        if($email_data['request']->action_name != 'ifwp-confirm-user-email'){
			return $email_text;
		}
		return $this->get_option('body');
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    function user_request_action_email_subject($subject, $sitename, $email_data){
        if($email_data['request']->action_name != 'ifwp-confirm-user-email'){
			return $subject;
		}
		return sprintf($this->get_option('subject'), $sitename, $email_data['description']);
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public function validate_password_reset($errors, $user){
        if($errors->has_errors()){
            return;
        }
        if(!is_a($user, 'WP_User')){
            return;
        }
        if(user_can($user->ID, $this->get_option('capability'))){
            return;
        }
        if($this->completed_user_requests($user)){
            return;
        }
        if(isset($_POST['pass1']) and $_POST['pass1']){ // validar si aún es necesario esto
			$this->fake_confirmation($user);
		}
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

}

$tab = new _IFWP_Confirm_User_Email('', 'Confirm user email');
$tab->add_switch([
    'id' => 'confirm_user_email',
    'name' => 'Confirm user email?',
    'std' => false,
]);
$tab->add_text([
    'id' => 'capability',
    'name' => '— Minimum capability required to bypass the user email confirmation:',
    'std' => 'manage_options',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_text([
    'id' => 'action',
    'name' => '— Action name:',
    'std' => 'Confirm your email address',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'id' => 'message',
    'name' => '— Confirmation message:',
    'rows' => 1,
    'std' => 'Thanks for confirming your email address.',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'desc' => 'Do not change DESCRIPTION, CONFIRM_URL, SITENAME, SITEURL: those are placeholders.',
    'id' => 'body',
    'name' => '— Email message:',
    'rows' => 15,
    'std' => 'Howdy,

A request has been made to perform the following action on your account:

###DESCRIPTION###

To confirm this, please click on the following link:
###CONFIRM_URL###

You can safely ignore and delete this email if you do not want to
take this action.

Regards,
All at ###SITENAME###
###SITEURL###',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'desc' => 'Do not change the placeholders. 1: Site title, 2: Name of the action.',
    'id' => 'subject',
    'name' => '— Email subject:',
    'rows' => 1,
    'std' => '[%1$s] Confirm Action: %2$s',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
$tab->add_field([
    'desc' => 'Do not change the placeholders. 1: Email address.',
    'id' => 'pending',
    'name' => '— Pending message:',
    'rows' => 1,
    'std' => 'Your email address has not been confirmed yet. Please check your inbox at %1$s for a confirmation email.',
    'type' => 'textarea',
    'visible' => array('confirm_user_email', true),
]);
if($tab->get_option('confirm_user_email', false)){
    $tab->on('admin_init', [$tab, 'admin_init']);
    $tab->on('authenticate', [$tab, 'authenticate'], 100);
    $tab->on('shake_error_codes', [$tab, 'shake_error_codes']);
    $tab->on('user_request_action_confirmed', [$tab, 'user_request_action_confirmed']);
    $tab->on('user_request_action_confirmed_message', [$tab, 'user_request_action_confirmed_message'], 10, 2);
    $tab->on('user_request_action_description', [$tab, 'user_request_action_description'], 10, 2);
    $tab->on('user_request_action_email_content', [$tab, 'user_request_action_email_content'], 10, 2);
    $tab->on('user_request_action_email_subject', [$tab, 'user_request_action_email_subject'], 10, 3);
    $tab->on('validate_password_reset', [$tab, 'validate_password_reset'], 10, 2);
}
