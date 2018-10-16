<?php

function count_collaborators( $post_id = 0 ) {
	
	if ( intval( $post_id ) == 0 ) {
		global $post;
		
		if ( isset( $post->ID ) ) {
		
			$post_id = $post->ID;
		}
	}
	
	if ( $post_id == 0 ) {
		return false;
	}
	
	$search_for_existing_users = get_field( 'search_for_existing_users', $post_id );
	$collaborators = get_field( 'collaborators', $post_id );
	
	
	
	$search_emails = array();
	
	if ( !empty( $collaborators ) ) {
		foreach ( $collaborators as $e ) {
			
			$user = get_user_by( 'email', $e['collaborator_email_address'] );
			
			// if the user exists add to array
			if ( $user ) {
				$search_emails[] = $e['collaborator_email_address'];				
			}
			
			
		}
	}
	
	if ( !empty( $search_for_existing_users ) ) {
		foreach ( $search_for_existing_users as $s ) {
			
			$user_mail = get_the_author_meta( 'user_email', $s );
			$search_emails[] = $user_mail;
		}
	}
	
	return count( array_unique( $search_emails ) );
}

// return an array of callaborator users by post ID
function get_collaborators( $post_id = 0 ) {
	
	if ( intval( $post_id ) == 0 ) {
		global $post;
		
		if ( isset( $post->ID ) ) {
		
			$post_id = $post->ID;
		}
	}
	

	
	if ( $post_id == 0 ) {
		return false;
	}
	
	
	
	$colabs_no = get_post_meta( $post_id, 'collaborators', true );
	$colabs_users = array();
	$colabs_user_emails = array();
	
	// add post author
	$author_id = get_post_field ( 'post_author', $post_id );
	$colabs_user_emails[] = get_the_author_meta( 'user_email', $author_id );
	
	$colabs_users[] = get_user_by( 'ID', $author_id );
	
	// get existing users
	$search_for_existing_users = get_field( 'search_for_existing_users', $post_id );
	
	if ( !empty( $search_for_existing_users ) ) {
		foreach ( $search_for_existing_users as $user_id ) {
			$user_obj = get_user_by( 'ID', $user_id );
			$colabs_users[] = $user_obj;
			$colabs_user_emails[] = $user_obj->user_email;
		}
	}
	
	
	for( $i=0; $i < $colabs_no; $i++ ) {
		
		$colab_email = get_post_meta( $post_id, 'collaborators_'. $i .'_collaborator_email_address', true );
		if ( !in_array( $colab_email, $colabs_user_emails ) ) {
			$colabs_user_emails[] = $colab_email;
			$user = get_user_by( 'email', $colab_email );
			
			// if the user exists add to array
			if ( $user ) {
				$colabs_users[] = $user;
			}
		}
	}
	
	return $colabs_users;
}

function get_collaborators_list( $post_id ) {
	
	if ( intval( $post_id ) == 0 ) {
		global $post;
		
		if ( isset( $post->ID ) ) {
		
			$post_id = $post->ID;
		}
	}
	
	if ( $post_id == 0 ) {
		return false;
	}
	
	if ( !is_user_logged_in() ) {
		
		$out= '
		<div class="colabs_wrap cs_sidebar_wrap">
			<h4 class="colabs_title">'. __( 'Innovation provided by:', 'opsi') .'</h4>
			<ul class="colab_list">
				<li class="author_li">
					<div class="row">
						<div class="col-md-4">
							
							'. get_default_avatar_img( 'thumb' ) .'
							
						</div>
						<div class="col-md-8">
							<h4>
								<a href="'. get_permalink( get_page_by_path('login') ) .'" title="'. __( 'Log In', 'opsi') .'">
								'. __( 'Log In to View', 'opsi') .'
								</a>
							</h4>
							
						</div>
					</div>
				</li>
			</ul>
		</div>
			';
		
		return $out;
	}
	
	$get_collaborators = get_collaborators( $post_id );
	
	if ( !$get_collaborators ) {
		return false;
	}
	

	$out= '
		<div class="colabs_wrap cs_sidebar_wrap">
			<h4 class="colabs_title">'. __( 'Innovation provided by:', 'opsi') .'</h4>
			<ul class="colab_list">';
	
	if ( !empty( $get_collaborators ) ) {
		
		foreach( $get_collaborators as $colab_user ) {
	
			$organisation = xprofile_get_field_data( 'Current organisation', $colab_user->ID );
			$organisation_website_link = xprofile_get_field_data( 'Organisation website', $colab_user->ID );			
			
			$organisation_website_xml = new SimpleXMLElement( $organisation_website_link );
			
			
			
			$out .= '<li class="author_li">
				<div class="row">
					<div class="col-md-4">
						<a href="'. bp_core_get_userlink( $colab_user->ID, false, true ) .'profile/" title="'. $colab_user->display_name .' '. __( 'profile', 'opsi' ) .'">
						'. bp_get_displayed_user_avatar( array('item_id' => $colab_user->ID, 'type'=>'thumb') ) .'
						</a>
					</div>
					<div class="col-md-8">
						<h4>
							'. $colab_user->first_name .' '. $colab_user->last_name .'
						</h4>
						<div class="organisation_li">
							'. ( !empty( $organisation_website_xml['href'] ) ? '<a href="'. $organisation_website_xml['href'][0]  .'" title="'. $organisation .'">' : '' ) .'
							'. $organisation .'
							'. ( !empty( $organisation_website_xml['href'][0] ) ? '</a>' : '' ) .'
						</div>
						<div class="user_email_li">
							<a href="'. bp_custom_get_send_private_message_link( $colab_user->ID ) .'" title="'. $colab_user->display_name .' '. __( 'profile', 'opsi' ) .'" class="button btn btn-primary btn-xs">
								'. __( 'Send message', 'opsi' ) .'
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</a>
						</div>
						<a href="'. bp_core_get_userlink( $colab_user->ID, false, true ) .'profile/" title="'. $colab_user->display_name .' '. __( 'profile', 'opsi' ) .'" class="button btn btn-info btn-xs">
							'. __( 'View profile', 'opsi' ) .'
							<i class="fa fa-chevron-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>
			</li>';
		
	
		}
	}
	
	$out .= '
			</ul>
		</div>
	';
	
		
	
	return $out;
	
}


function get_default_avatar_url() {
	
	return get_stylesheet_directory_uri() .'/images/default-avatar.png';
}
function get_default_avatar_img( $class='' ) {
	return '<img src="'. get_default_avatar_url() .' " class="img-circle default_avatar '. $class .'" />';
}