<?php


add_shortcode( 'userinfo', 'userinfo_func' );
function userinfo_func ( $atts ) {
	
	$a = shortcode_atts( array(
        'id' => '',
        'attr' => 'display_name',
    ), $atts );
	
	if ( intval( $a['id'] ) > 0 ) {
		$user_id = $a['id'];
		$user = get_userdata( $user_id );
	} else {
		$user = wp_get_current_user();
	}
	
	return $user->{$a['attr']};
}

add_shortcode( 'profilelink', 'profilelink_func' );
function profilelink_func ( $atts ) {
	
	$a = shortcode_atts( array(
        'user_id' => '',
        'text' => '',
        'title' => '',
        'class' => '',
    ), $atts );
	
	if ( intval( $a['user_id'] ) > 0 ) {
		return '<a href="'. bp_core_get_user_domain( $user_id ) .'innovations/" class="'. $a['class'] .'" title="'. $a['title'] .'">'. $a['text'] .'</a>';
	} else {
		$user = wp_get_current_user();
		return '<a href="'. bp_core_get_user_domain( $user->ID ) .'innovations/" class="'. $a['class'] .'" title="'. $a['title'] .'">'. $a['text'] .'</a>';
	}
}

add_shortcode( 'invited_by_name', 'invited_by_name_func' );
function invited_by_name_func ( $atts ) {
	
	$a = shortcode_atts( array(
        'user_id' => '',
        'title' => '',
        'class' => '',
    ), $atts );
	
	if ( intval( $a['user_id'] ) == 0 ) {
		$user_id = get_current_user_id();
	}
	
	$userdata = get_userdata( $user_id );
	
	
	return '<a href="'. bp_core_get_user_domain( $userdata->ID ) .'" class="'. $a['class'] .'" title="'. $a['title'] .'">'. $userdata->display_name .'</a>';
}



