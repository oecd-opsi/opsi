<?php


if( function_exists('acf_add_options_page') ) {


	function acf_to_content( $content ) {

		// no need to run this on not single pages/posts etc
		if ( !is_singular() ) {
			return $content;
		}

		if ( !in_the_loop() ) {
			return $content;
		}

		global $post;

		ob_start();

		if( have_rows( 'row', $post->ID ) ) {

			// loop through the rows of data
			while ( have_rows( 'row', $post->ID ) ) {

				$row = the_row();



				if( have_rows('row_content') ) {
					?>
					<div class="row">
					  <?php
					  // loop through the rows of data
					  while ( have_rows('row_content', $post->ID) ) {
						the_row( );

						get_template_part( '/blocks/'.get_row_layout( ) ); // fails silently

					  }
					  ?>
					</div>
					<?php
				}

			}

		} else {

			return $content;

		}

		$content .= '<div class="clear clearfix"></div>'.ob_get_contents();

		ob_end_clean();

		return $content;
	}
	add_filter('the_content', 'acf_to_content');



	$option_page = acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability' 	=> 'edit_posts',
		'redirect' 		=> false
	));

}

add_action('acf/validate_save_post', 'cs_save_acf_validate_save_post', 10);
function cs_save_acf_validate_save_post() {

	// if it is a "SAVE for later"
	if( ( isset( $_POST['csf_action'] ) && ( $_POST['csf_action'] == 'save' || $_POST['csf_action'] == 'saveandpreview' ) ) || strpos( $_SERVER['HTTP_REFERER'], '/wp-admin') !== false ) { //|| is_admin() // current_user_can( 'manage_options' )

		// clear all errors
		acf_reset_validation_errors();

	}

}


add_filter( "acf/load_field/key=field_5af38f0363c47", 'nitro_case_study_form_first_page' );
function nitro_case_study_form_first_page ( $field ) {

	// OECD form
	$case_study_form_page = get_field( 'case_study_form_page', 'option' );

	if ( !is_admin() && !empty( $case_study_form_page ) && is_page( $case_study_form_page ) ) {
		$field['label'] 	= '';
		$field['message'] 	= apply_filters( 'the_content', get_post_field( 'post_content', get_field( 'case_study_form_first_page', 'option' ) ) );
	}

	// Open Government form
	$case_study_form_page = get_field( 'case_study_form_page_open_gov', 'option' );

	if ( !is_admin() && !empty( $case_study_form_page ) && is_page( $case_study_form_page ) ) {
		$field['label'] 	= '';
		$field['message'] 	= apply_filters( 'the_content', get_post_field( 'post_content', get_field( 'case_study_form_first_page_open_gov', 'option' ) ) );
	}

	return $field;
}


add_filter( "acf/load_field/name=year_innovation_launched", 'nitro_case_study_form_year_field' );
function nitro_case_study_form_year_field ( $field ) {

	$field['max'] = date('Y') - 0;
	$field['min'] = date('Y') - 18;

	return $field;
}


add_filter( "acf/load_value/name=cs_user_email", 'nitro_case_study_form_user_email', 10, 3 );
function nitro_case_study_form_user_email ( $value, $post_id, $field ) {

	if ( is_user_logged_in() && !is_admin() ) {

		$wp_get_current_user = wp_get_current_user();
		$value = $wp_get_current_user->data->user_email;
	}
	return $value;
}


add_filter( "acf/load_value/name=cs_user_name", 'nitro_case_study_form_user_name', 10, 3 );
function nitro_case_study_form_user_name ( $value, $post_id, $field ) {

	if ( is_user_logged_in() && !is_admin() ) {

		$wp_get_current_user = wp_get_current_user();
		$value = $wp_get_current_user->data->display_name;
	}
	return $value;
}


add_filter( "acf/load_value/name=cs_user_organization", 'nitro_case_study_form_user_organisation', 10, 3 );
function nitro_case_study_form_user_organisation ( $value, $post_id, $field ) {

	if ( is_user_logged_in() && !is_admin() ) {

		$user_id = bp_loggedin_user_id();
		$value = bp_get_profile_field_data('field=Current organisation&user_id='.$user_id);
	}
	return $value;
}


add_filter('acf-input-counter/display', 'opsi_acf_counter_filter');
function opsi_acf_counter_filter($display) {
    $display = sprintf(
        __('%1$s / %2$s', 'acf-counter'),
        '%%len%%',
        '%%max%%'
    );
	return $display;
}

// manipulate the case study AFTER it has been saved
add_action('acf/save_post', 'opsi_acf_save_post', 11);
function opsi_acf_save_post( $post_id ) {

	if ( get_post_type( $post_id ) != 'case' ) {
		return;
	}

    $summary = get_field( 'describing_the_innovation', $post_id );

	$content = array(
		'ID' => $post_id,
		'post_title' => ( $summary['name_of_innovation'] == '' || empty( $summary['name_of_innovation'] ) ?  __( 'Untitled case study', 'opsi' ) : $summary['name_of_innovation'] ) ,
		'post_content' => ''
	);

	if( isset( $_POST['csf_action'] ) && $_POST['csf_action'] == 'submit' &&!is_admin() ) {
		$content['post_status'] = 'pending';
	}

	wp_update_post($content);

	// set the features image
	$material = get_field( 'materials_&_short_explanation', $post_id );
	$photo_and_video = $material['photo_and_video'];
	$upload_images = $photo_and_video['upload_images'];

	if ( !empty( $upload_images ) ) {
		set_post_thumbnail( $post_id, $upload_images[0]['ID'] );
	}

	// subscribe user on MailChimp if radio button is set

	$wants_to_subscribe = get_field( 'questionnaire_feedback_miscellaneous_newsletter_register', $post_id );

	if ( class_exists( 'MC4WP_MailChimp') && $wants_to_subscribe == 'yes' ) {

		$MC4WP_MailChimp 		= new MC4WP_MailChimp();

		$subscriber['status'] 	= 'subscribed';
		$list_id 				= '8445d592ef';
		$post_author_id 		= get_post_field( 'post_author', $post_id );
		$email_address 			= get_the_author_meta( 'user_email', $post_author_id );

		$MC4WP_MailChimp->list_subscribe( $list_id, $email_address, $subscriber );


	}


}


// redirect to the proper page
add_action('acf/submit_form', 'case_study_redirect_acf_submit_form', 10, 2);
function case_study_redirect_acf_submit_form( $form, $post_id ) {

	if( $_POST['csf_action'] == 'submit' ) {

		$thankyou_page = get_field( 'case_study_form_thankyou_page_submit', 'option' );

		wp_safe_redirect( get_the_permalink( $thankyou_page ) );
		die;
	}

	if( $_POST['csf_action'] == 'save' ) {

		$case_study_form_page = $_SERVER['REQUEST_URI'];

		$step = ( isset( $_POST['form_step'] ) && intval( $_POST['form_step'] ) > 0 ? intval( $_POST['form_step'] ) : 0 );

		wp_safe_redirect( get_the_permalink( $case_study_form_page ).'?edit='.$post_id.'&updated=true#step-'.$step );
		die;
	}

	if( $_POST['csf_action'] == 'saveandpreview' ) {

		wp_safe_redirect( get_the_permalink( $post_id ) );
		die;
	}

}



add_filter('acf/pre_save_post' , 'case_study_save_collaborators', 10, 1 );
function case_study_save_collaborators( $post_id ) {


	// check if there is a case study ID
	if ( !isset( $_POST['cs_id'] ) ) {
		return $post_id;
	} else {
		$cs_id = intval( $_POST['cs_id'] );
	}

	// check if the user can edit the collaborators
	if ( !can_edit_acf_form( $cs_id ) ) {
		return $post_id;
	}

	// remove empty email fields
	$email_indx = 0;
	if ( !empty ( $_POST['acf']['field_5b3ffdbdd0c3d'] ) ) {
		foreach ( $_POST['acf']['field_5b3ffdbdd0c3d'] as $single_email_array ) {

			if ( $single_email_array['field_5b3ffdd3d0c3e'] == '' ) {
				unset( $_POST['acf']['field_5b3ffdbdd0c3d'][$email_indx] );
			}

			$email_indx++;
		}
	}

	$post_emails = $_POST['acf'];

	if ( empty( $post_emails ) ) {
		return $post_id;
	}


	$ids_array = $post_emails['field_5b4f3f838f021'];
	$emails_array = $post_emails['field_5b3ffdbdd0c3d'];


	if ( empty( $emails_array ) && empty( $ids_array ) ) {
		return $post_id;
	}

	$all_emails = array();
	if ( !empty( $emails_array ) ) {
		foreach( $emails_array as $single_mail_array) {
			$email_addr = current( $single_mail_array );
			if ( $email_addr != '' ) {
				$all_emails[] = $email_addr;
			}
		}
	}

	$old_colabs = get_field( 'collaborators', $cs_id );
	$old_colabs_array = array();

	if ( !empty( $old_colabs ) ) {
		foreach ( $old_colabs as $old_col ) {
			$old_colabs_array[] = $old_col['collaborator_email_address'];
		}
	}

	delete_field( 'collaborators', $cs_id );

	// get Collaboration email subject and Collaborators email content based on case type
	$case_type = get_the_terms( $post_id, 'case_type' );
	$case_type_slug = $case_type[0]->slug;
	if ( $case_type_id == 'opsi' ) {
		//OPSI-OECD case type
		$collaborators_subject = get_field( 'collaborators_email_subject', 'option' );
		$collaborators_email_content = get_field( 'collaborators_email', 'option' );
	} else {
		//Open gov case type
		$collaborators_subject = get_field( 'collaborators_email_subject_open_gov', 'option' );
		$collaborators_email_content = get_field( 'collaborators_email_open_gov', 'option' );
	}

	// replace shortcode content
	if ( strpos( $collaborators_email_content, '[innovation_name' ) !== false ) {
		$collaborators_email_content =  sprintf( $collaborators_email_content, get_the_title( $cs_id ) );
	}

	// check if the user already exists, if not send an email // else add to the existing users dropdown
	foreach( $all_emails as $single_email ) {

		$user = get_user_by( 'email', $single_email );

		// if the user does not exists
		if ( !$user ) {

			// do not send invitation to the same user for the same case study
			if ( in_array( $single_email, $old_colabs_array ) ) {
				continue;
			}

			$collaborators_email_content = nl2br( do_shortcode( $collaborators_email_content ) );
			$subject = $collaborators_subject;
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail( $single_email, $subject, $collaborators_email_content, $headers );

		}
	}
	return $post_id;

}

// prepopulate Collaborators emails field
// add_filter('acf/load_value/name=collaborators' , 'case_study_load_collaborators', 10, 3 );
function case_study_load_collaborators($value, $post_id, $field) {

	if ( is_admin() ) {
		return $value;
	}

	if ( !isset( $_GET['edit'] ) ) {
		return false;
	} else {
		$cs_id = intval( $_GET['edit'] );
	}

	// check if the user can edit the collaborators
	if ( !can_edit_acf_form( $cs_id ) ) {
		return false;
	}


	$no_existing_colabs = get_post_meta( $cs_id, 'collaborators', true );
	$existing_values = array();
	for ( $i = 0; $i <  $no_existing_colabs; $i++ ) {
		$existing_values[$i]['field_5b3ffdd3d0c3e'] = get_post_meta( $cs_id, 'collaborators_'. $i .'_collaborator_email_address', true );
	}

	return $existing_values;
}


function get_all_acf_fields_by_group_key( $group_key, $skip = false ) {

	$out = array();

	$all_acf_fields = acf_get_fields( $group_key );

	foreach( $all_acf_fields as $acf_groups ) {

		if ( $acf_groups['type'] == 'group' && !( isset( $acf_groups['display_order'] ) && $acf_groups['display_order'] == -1 && $skip === true ) ) {

			foreach ( $acf_groups['sub_fields'] as $acf_group ) {

				if ( $acf_group['name'] != '' && !( isset( $acf_group['display_order'] ) && $acf_group['display_order'] == -1 && $skip === true ) ) {
					$out[''.$acf_groups['name'].'']['label'] = $acf_groups['label'];
					$out[''.$acf_groups['name'].'']['display_order'] = ( isset( $acf_groups['display_order'] ) ? $acf_groups['display_order'] : 10 );
					$out[''.$acf_groups['name'].''][''.$acf_group['name'].'']['text'] = get_field( $acf_groups['name'].'_'. $acf_group['name'] );
					$out[''.$acf_groups['name'].''][''.$acf_group['name'].'']['display_order'] = ( isset( $acf_group['display_order'] ) ? $acf_group['display_order'] : 10 );
				}

			}

		}

	}

	return $out;

}

function get_textarea_acf_fields_by_group_key( $group_key, $skip = false ) {

	$out = array();

	$all_acf_fields = acf_get_fields( $group_key );
	foreach( $all_acf_fields as $acf_groups ) {

		if ( $acf_groups['type'] == 'group' && !( isset( $acf_groups['display_order'] ) && $acf_groups['display_order'] == -1 && $skip === true ) ) {

			foreach ( $acf_groups['sub_fields'] as $acf_group ) {

				if ( $acf_group['name'] != '' && !( isset( $acf_group['display_order'] ) && $acf_group['display_order'] == -1 && $skip === true ) ) {
					$out[''.$acf_groups['name'].'']['label'] = $acf_groups['label'];
					$out[''.$acf_groups['name'].'']['display_order'] = ( isset( $acf_groups['display_order'] ) ? $acf_groups['display_order'] : 10 );
					if ( $acf_group['type'] == 'textarea' ) {
						$out[''.$acf_groups['name'].''][''.$acf_group['name'].'']['text'] = get_field( $acf_groups['name'].'_'. $acf_group['name'] );
						$out[''.$acf_groups['name'].''][''.$acf_group['name'].'']['display_order'] = ( isset( $acf_group['display_order'] ) ? $acf_group['display_order'] : 10 );


						if (strpos($acf_group['wrapper']['class'], 'removelabel') === false) {
							$out[''.$acf_groups['name'].''][''.$acf_group['name'].'']['label'] = $acf_group['label'];
						} else {
							$out[''.$acf_groups['name'].''][''.$acf_group['name'].'']['label'] = '_removed';
						}
					}
				}

			}

		}

	}

	return $out;

}

function get_options_acf_fields_by_group_key( ) {

	$out = '';

	$all_acf_fields = acf_get_fields( 'group_597ebdb66a7e1' );

	foreach( $all_acf_fields as $acf_options ) {


		if ( $acf_options['wrapper']['class'] == 'csform_example' ) {

		// echo '<pre>'.print_r(get_field( $acf_options['name'], 'option' ), true).'</pre>';
			$out .= '
				<div style="display: none;" id="'. $acf_options['wrapper']['id'] .'" class="cs_form_modal" >
					'. get_field( $acf_options['name'], 'option' ) .'
				</div>
			';

		}

	}

	return $out;

}

add_filter('acf/prepare_field/name=search_for_existing_users', 'opsi_remove_collaborators_user_drop');
function opsi_remove_collaborators_user_drop( $field ) {

	if ( !is_user_logged_in() ) {
		return false;
	}

	$user = wp_get_current_user();

	if ( in_array( 'pending', (array) $user->roles ) ) {
		return false;
	}

    return $field;

}


/***** ADMIN EXTRA FIELDS START *****/

add_action('acf/render_field_settings', 'opsi_admin_only_render_field_settings');

function opsi_admin_only_render_field_settings( $field ) {

	acf_render_field_setting( $field, array(
		'label'			=> __( 'Display Order', 'opsi' ),
		'instructions'	=> __( 'Sets the order of the field wherever applicable. Set to -1 to skip the field.', 'opsi' ),
		'name'			=> 'display_order',
		'type'			=> 'number'
	), true);

}


/***** ADMIN EXTRA FIELDS END *****/
