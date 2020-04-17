<?php

$tab = new _IFWP_Tab('', 'Login');
$tab->add_switch([
    'id' => 'custom_login',
    'name' => 'Custom login?',
    'std' => false,
]);
$tab->add_field([
    'id' => 'logo',
    'max_file_uploads' => 1,
    'max_status' => false,
    'name' => '— Logo:',
    'type' => 'image_advanced',
    'visible' => array('custom_login', true),
]);
if($tab->get_option('custom_login', false)){
    $logo = $tab->get_option('logo', []);
    if($logo){
        $tab->on('login_enqueue_scripts', function() use($logo){ ?>
            <style type="text/css">
                #login h1 a,
                .login h1 a {
                    background-image: url(<?php echo $logo['url']; ?>);
            		height: 150px;
            		width: 150px;
            		background-size: 150px;
                }
            </style><?php
        });
    }
    $tab->on('login_headertitle', function(){
        return get_option('blogname');
    });
    $tab->on('login_headerurl', 'home_url');
}
