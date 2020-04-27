<?php

$tab = new _IFWP_Tab('', 'Media');
$tab->add_switch([
    'label_description' => 'Sizes: hd and full-hd.',
    'id' => 'add_larger_image_sizes',
    'name' => 'Add larger image sizes?',
    'std' => true,
]);
if($tab->get_option('add_larger_image_sizes', true)){
    add_image_size('hd', 1280, 1280);
    add_image_size('full-hd', 1920, 1920);
    $tab->on('image_size_names_choose', function($sizes){
        $sizes['hd'] = 'HD';
        $sizes['full-hd'] = 'Full HD';
		return $sizes;
    });
}
$tab->add_switch([
    'id' => 'remove_accents',
    'name' => 'Remove accents?',
    'std' => true,
]);
if($tab->get_option('remove_accents', true)){
    $tab->on('sanitize_file_name', 'remove_accents');
}
$tab->add_switch([
    'label_description' => 'Filetypes: audio and video.',
    'id' => 'solve_conflicts',
    'name' => 'Solve filetype conflicts?',
    'std' => true,
]);
if($tab->get_option('solve_conflicts', true)){
    $tab->on('wp_check_filetype_and_ext', function($wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime){
		if($wp_check_filetype_and_ext['ext'] and $wp_check_filetype_and_ext['type']){
			return $wp_check_filetype_and_ext;
		}
		if(0 === strpos($real_mime, 'audio/') or 0 === strpos($real_mime, 'video/')){
			$filetype = wp_check_filetype($filename);
			if(in_array(substr($filetype['type'], 0, strcspn($filetype['type'], '/')), array('audio', 'video'))){
				$wp_check_filetype_and_ext['ext'] = $filetype['ext'];
				$wp_check_filetype_and_ext['type'] = $filetype['type'];
			}
		}
		return $wp_check_filetype_and_ext;
	}, 10, 5);
}
