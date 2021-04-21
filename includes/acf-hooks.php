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

		if( have_rows( 'row', $post->ID ) ) {

			ob_start();

			// loop through the rows of data
			while ( have_rows( 'row', $post->ID ) ) {

				$row = the_row();

				if( have_rows('row_content') ) {
					?>
					<div class="row">
					  <?php
					  // loop through the rows of data
					  while ( have_rows('row_content') ) {
						the_row( );

						get_template_part( '/blocks/'.get_row_layout() ); // fails silently

					  }
					  ?>
					</div>
					<?php
				}

			}

			$content .= '<div class="clear clearfix"></div>'.ob_get_contents();

			ob_end_clean();

		}

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

add_filter( "acf/load_field/key=field_5e8caf4dd7d17", 'nitro_covid_response_form_first_page' );
function nitro_covid_response_form_first_page ( $field ) {

	// form page
	$covid_response_form_page = get_field( 'covid_response_form_page', 'option' );

	if ( !is_admin() && ! empty( $covid_response_form_page ) && is_page( $covid_response_form_page ) ) {
		$field['label']   = '';
		$field['message'] = apply_filters( 'the_content', get_post_field( 'post_content', get_field( 'covid_response_form_first_page', 'option' ) ) );
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

// Render Covid Response textarea fields as wysiwyg on backend
add_filter( 'acf/prepare_field/name=innovative_response_description', 'opsi_render_textarea_as_wysiwyg' );
add_filter( 'acf/prepare_field/name=specific_issue_addressed', 'opsi_render_textarea_as_wysiwyg' );
add_filter( 'acf/prepare_field/name=organisations_involved', 'opsi_render_textarea_as_wysiwyg' );
add_filter( 'acf/prepare_field/name=potential_issues', 'opsi_render_textarea_as_wysiwyg' );
function opsi_render_textarea_as_wysiwyg( $field ) {
	if ( is_admin() && 'textarea' == $field['type'] ) {
		$field['type'] = 'wysiwyg';
	}
	return $field;
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
add_action('acf/save_post', 'opsi_acf_save_post_case_study', 11);
function opsi_acf_save_post_case_study( $post_id ) {

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

	// set the featured image
	$material = get_field( 'materials_&_short_explanation', $post_id );
	$photo_and_video = $material['photo_and_video'];
	$upload_images = $photo_and_video['upload_images'];

	if ( !empty( $upload_images ) ) {
		set_post_thumbnail( $post_id, $upload_images[0]['ID'] );
	}

	// subscribe user on MailChimp if radio button is set
	$wants_to_subscribe = get_field( 'questionnaire_feedback_miscellaneous_newsletter_register', $post_id );
	if ( $wants_to_subscribe == 'yes' ) {
		$post_author_id 		= get_post_field( 'post_author', $post_id );
		$email_address 			= get_the_author_meta( 'user_email', $post_author_id );
		opsi_subscribe_to_mailchimp( $email_address );
	}

}

// manipulate the Covid Response AFTER it has been saved
add_action('acf/save_post', 'opsi_acf_save_post_covid_response', 20 );
function opsi_acf_save_post_covid_response( $post_id ) {

	if ( get_post_type( $post_id ) != 'covid_response' ) {
		return;
	}

	// get data
	$info     = get_field( 'information_about_the_response', $post_id );
	$material = get_field( 'materials_and_submission', $post_id );
	$title    = ( empty( $info['innovative_response_short_title'] ) ? __( 'Untitled Covid Response', 'opsi' ) : $info['innovative_response_short_title'] );

	// set title
	$content = array(
		'ID'           => $post_id,
		'post_title'   => $title,
		'post_content' => '',
		'post_name'    => sanitize_title( $title ),
	);
	wp_update_post( $content );

	// set the featured image
	$upload_images = $material['upload_images'];
	if ( !empty( $upload_images ) ) {
		set_post_thumbnail( $post_id, $upload_images[0]['image']['ID'] );
	}

	// Send submission to user via email
	if ( ! is_admin() ) {
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$permalink = get_permalink( $post_id );
		$body = '<p>' . sprintf( __( 'Thank you for submitting your Covid-19 Innovative Response to OPSI. Your response is now live on the OPSI website at %s.', 'opsi' ), "<a href='$permalink'>$permalink</a>" ) . '</p>';
		$body .= '<p>' . __( 'Please find below a recap of the data submitted', 'opsi' ) . '</p>';

		$field_groups = [
			'information_about_the_response' => [
				'field_5e8cb0ccd7d19', // innovative_response_description
				'field_5e8cb127d7d1a', // general_issues_addressed
				'field_5e8cb19cd7d1c', // specific_issue_addressed
				'field_5e8cb22dd7d1d', // innovative_response_short_title
				'field_5e8cb88c9bd46', // organisations_involved
				'field_5e8cb8fb9bd47', // potential_issues
				'field_5e8cb9249bd48', // levels_of_government
				'field_5e8cbf8a5ea83', // primary_url
				'field_5e8cbfa55ea84', // other_urls
				'field_5e8cbfd45ea85', // country
				'field_5e8cc02b5ea86', // email
				'field_5e8cc0495ea87', // email_public
			],
			'materials_and_submission' => [
				'field_5e95e4293128c', // upload_images
				'field_5e95e889e7e53', // upload_files
				'field_5e8cbaa326c69', // register_for_newsletter
			],
		];

		foreach ( $field_groups as $field_group_name => $field_group_fields ) {
			$field_group  = get_field( $field_group_name, $post_id );
			foreach ( $field_group_fields as $field ) {
				$field_object = get_field_object( $field, $post_id );
				if ( ! empty( $field_object ) ) {
					$body  .= '<p><strong>' . $field_object['label'] . '</strong></p>';
					$value = $field_group[ $field_object['name'] ];
					switch ( $field_object['type'] ) {
						case 'checkbox':
							$body .= '<p>';
							foreach ( $value as $subvalue ) {
								$body .= $subvalue;
								$body .= '<br>';
							}
							$body .= '</p>';
							break;
						case 'taxonomy':
							$body .= '<p>';
							$terms = wp_get_post_terms( $post_id, $field_object['taxonomy'], [ 'fields' => 'names' ] );
							foreach ( $terms as $term ) {
								$body .= $term;
								$body .= '<br>';
							}
							$body .= '</p>';
							break;
						case 'gallery':
							$body .= '<p>';
							foreach ( $value as $image ) {
								$body .= $image['url'];
								$body .= '<br>';
							}
							$body .= '</p>';
							break;
						case 'textarea':
							$body .= $value;
							break;
						case 'repeater':
							if ( isset( $value[0]['image'] ) ) {
								$body .= '<p>';
								foreach ( $value as $image ) {
									$body .= $image['image']['url'];
									$body .= '<br>';
								}
								$body .= '</p>';
							}
							if ( isset( $value[0]['file'] ) ) {
								$body .= '<p>';
								foreach ( $value as $file ) {
									$body .= $file['file']['url'];
									$body .= '<br>';
								}
								$body .= '</p>';
							}
							break;
						case 'text':
						case 'url':
						case 'email':
						case 'radio':
						default:
							$body .= '<p>' . $value . '</p>';
							break;
					}
				}
			}
		}

		$body .= 'Best regards,<br>The OPSI team';

		wp_mail( $info['email'], __( 'Your Covid-19 Innovative Response submission', 'opsi' ), $body, $headers );
	}

	// subscribe user on MailChimp if radio button is set
	if ( 'yes' === $material['register_for_newsletter'] ) {
		opsi_subscribe_to_mailchimp( $info['email'] );
	}

}

// manipulate the BI project AFTER it has been saved
add_action('acf/save_post', 'opsi_acf_save_post_bi_project', 11);
function opsi_acf_save_post_bi_project( $post_id ) {

	if ( get_post_type( $post_id ) != 'bi-project' ) {
		return;
	}

  $summary = get_field( 'who_is_behind_the_project', $post_id );

	$content = array(
		'ID' => $post_id,
		'post_title' => ( $summary['project_name'] == '' || empty( $summary['project_name'] ) ?  __( 'Untitled project', 'opsi' ) : $summary['project_name'] ) ,
		'post_content' => ''
	);

	wp_update_post($content);

}

// Subscribes a user to Mailchimp
function opsi_subscribe_to_mailchimp( $email_address ) {
	if ( class_exists( 'MC4WP_MailChimp' ) ) {
		$args['status']  = 'subscribed';
		$list_id         = '8445d592ef';
		$MC4WP_MailChimp = new MC4WP_MailChimp();
		$MC4WP_MailChimp->list_subscribe( $list_id, $email_address, $args );
	}
}

// redirect to the proper page after Case study submission
add_action('acf/submit_form', 'case_study_redirect_acf_submit_form', 10, 2);
function case_study_redirect_acf_submit_form( $form, $post_id ) {

	if ( 'case-study-form' !== $form['id'] ) {
		return;
	}

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

// redirect to the proper page after Covid response submission
add_action('acf/submit_form', 'covid_response_redirect_acf_submit_form', 10, 2);
function covid_response_redirect_acf_submit_form( $form, $post_id ) {

	if ( 'covid-response-form' !== $form['id'] ) {
		return;
	}

	if( $_POST['csf_action'] == 'submit' ) {

		$thankyou_page = get_field( 'covid_response_form_thank_you_page_submit', 'option' );

		wp_safe_redirect( get_the_permalink( $thankyou_page ) . '?id=' . $post_id );
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

function get_options_acf_fields_by_group_key( $group, $class ) {

	$out = '';

	$all_acf_fields = acf_get_fields( $group );

	foreach( $all_acf_fields as $acf_options ) {


		if ( $acf_options['wrapper']['class'] == $class ) {

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


// Send notification emails when a new toolkit has been submitted
add_action( 'acf/save_post', 'opsi_save_toolkit' );
function opsi_save_toolkit( $post_id ) {
	$post = get_post( $post_id );

	// bail early if not a toolkit post
	if ( $post->post_type != 'toolkit' ) {
		return;
	}

	// bail early if editing in admin
	if ( is_admin() ) {
		return;
	}

	$users = get_users(
		array(
			'role' => 'editor'
		)
	);

	$subject = __( 'A new toolkit has been submitted', 'opsi' );
	$message = sprintf( "A new toolkit has been submitted and requires the approval of an administrator to be published.\r\n You can view, edit or approve the new toolkit at the following address (login is required): %s.", get_edit_post_link( $post_id, '' ) );

	foreach( $users as $user ) {
		$to = $user->user_email;
		wp_mail( $to, $subject, $message );
	}

}


if( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Featured',
		'menu_title'	=> 'Featured',
		'parent_slug'	=> 'edit.php?post_type=toolkit',
	));

}
