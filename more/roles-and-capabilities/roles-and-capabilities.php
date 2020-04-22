<?php

$tab = new _IFWP_Tab('', 'Roles and capabilities');
$tab->add_switch([
    'id' => 'hide_dashboard',
    'name' => 'Hide the dashboard?',
    'std' => true,
]);
$tab->add_text([
    'id' => 'hide_dashboard_capability',
    'name' => '— Minimum capability required to show the dashboard:',
    'std' => 'edit_posts',
    'visible' => array('hide_dashboard', true),
]);
if($tab->get_option('hide_dashboard', true)){
    $tab->on('admin_init', function() use($tab){
        if(wp_doing_ajax()){
            return;
        }
        if(current_user_can($tab->get_option('hide_dashboard_capability', 'edit_posts'))){
            return;
        }
        wp_safe_redirect(home_url());
        exit;
    });
}
$tab->add_switch([
    'id' => 'hide_toolbar',
    'name' => 'Hide the toolbar?',
    'std' => true,
]);
$tab->add_text([
    'id' => 'hide_toolbar_capability',
    'name' => '— Minimum capability required to show the toolbar:',
    'std' => 'edit_posts',
    'visible' => array('hide_toolbar', true),
]);
if($tab->get_option('hide_toolbar', true)){
    $tab->on('show_admin_bar', function($show) use($tab){
        if(!current_user_can($tab->get_option('hide_toolbar_capability', 'edit_posts'))){
            return false;
        }
        return $show;
    });
}
$tab->add_switch([
    'id' => 'hide_media',
    'name' => 'Hide others media?',
    'std' => true,
]);
$tab->add_text([
    'id' => 'hide_media_capability',
    'name' => '— Minimum capability required to show others media:',
    'std' => 'edit_others_posts',
    'visible' => array('hide_media', true),
]);
if($tab->get_option('hide_media', true)){
    $tab->on('ajax_query_attachments_args', function($query) use($tab){
        if(!current_user_can($tab->get_option('hide_media_capability', 'edit_others_posts'))){
            $query['author'] = get_current_user_id();
        }
        return $query;
    });
}
$tab->add_switch([
    'id' => 'hide_posts',
    'name' => 'Hide others posts?',
    'std' => true,
]);
$tab->add_text([
    'id' => 'hide_posts_capability',
    'name' => '— Minimum capability required to show others posts:',
    'std' => 'edit_others_posts',
    'visible' => array('hide_posts', true),
]);
if($tab->get_option('hide_posts', true)){
    $tab->on('current_screen', function($current_screen) use($tab){
        global $pagenow;
        if($pagenow != 'edit.php'){
            return;
        }
        if(!current_user_can($tab->get_option('hide_posts_capability', 'edit_others_posts'))){
            add_filter('views_' . $current_screen->id, function($views){
                foreach($views as $index => $view){
                    $views[$index] = preg_replace('/ <span class="count">\([0-9]+\)<\/span>/', '', $view);
                }
                return $views;
            });
        }
    });
    $tab->on('pre_get_posts', function($query) use($tab){
        global $pagenow;
        if('edit.php' != $pagenow or !$query->is_admin){
            return $query;
        }
        if(!current_user_can($tab->get_option('hide_posts_capability', 'edit_others_posts'))){
            $query->set('author', get_current_user_id());
        }
        return $query;
    });
}
$tab->add_switch([
    'id' => 'hide_rest_api',
    'name' => 'Hide the REST API?',
]);
$tab->add_text([
    'id' => 'hide_rest_api_capability',
    'name' => '— Minimum capability required to show the REST API:',
    'std' => 'read',
    'visible' => array('hide_rest_api', true),
]);
if($tab->get_option('hide_rest_api', false)){
    $tab->on('rest_authentication_errors', function($error) use($tab){
        if($error){
            return $error;
        }
        if(!current_user_can($tab->get_option('hide_rest_api_capability', 'read'))){
            return new WP_Error('rest_user_cannot_view', __('You need a higher level of permission.'), [
                'status' => 401,
            ]);
        }
        return null;
    });
}
$tab->add_switch([
    'id' => 'hide_site',
    'name' => 'Hide the entire site?',
    'std' => false,
]);
$tab->add_text([
    'id' => 'hide_site_capability',
    'name' => '— Minimum capability required to show the entire site:',
    'std' => 'read',
    'visible' => array('hide_site', true),
]);
$tab->add_field([
    'id' => 'hide_site_special',
    'label_description' => 'For details, see <a href="https://developer.wordpress.org/themes/basics/conditional-tags/#the-conditions-for" target="_blank">The Conditions For</a>.',
    'name' => '— Exclude special pages:',
    'options' => array(
        'front_end' => 'Front end',
        'home' => 'Home',
    ),
    'placeholder' => 'Select pages',
    'std' => 'front_end',
    'type' => 'select_advanced',
    'visible' => array('hide_site', true),
]);
$tab->add_field([
    'id' => 'hide_site_excluded',
    'multiple' => true,
    'name' => '— Exclude other pages:',
    'placeholder' => 'Select pages',
    'post_type' => 'page',
    'type' => 'post',
    'visible' => array('hide_site', true),
]);
if($tab->get_option('hide_site', false)){
    $tab->on('template_redirect', function() use($tab){
        if(!in_array(get_the_ID(), $tab->get_option('hide_site_excluded', []))){
            if(is_front_page() and in_array('front_end', $tab->get_option('hide_site_special', []))){
                return;
            }
            if(is_home() and in_array('home', $tab->get_option('hide_site_special', []))){
                return;
            }
            if(!is_user_logged_in()){
                auth_redirect();
            } else {
                if(!current_user_can($tab->get_option('hide_site_capability', 'read'))){
                    wp_die('<h1>' . __('You need a higher level of permission.') . '</h1>' . '<p>' . __('Sorry, you are not allowed to access this page.') . '</p>', 403);
                }
            }
        }
    });
}
