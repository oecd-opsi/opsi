<?php
/*
Template Name: Open Government Case study form
Template Post Type: page
*/

/*
** ACF filter to customize Open Government form
*/
// give value to case type taxonomy field
function bs_case_study_form_taxonomy_field($field) {
	$field['value'] = 891;
  return $field;
}
add_filter('acf/prepare_field/key=field_5bc60b5d239b4', 'bs_case_study_form_taxonomy_field', 20);

// Hide fields
function bs_case_study_open_gov_hide_fields($field) {

  $fields_hidden = array(
		'public_employee_involved',
		'public_official_involved',
		'cs_user_name',
		'cs_user_organization',
		'cs_user_email',
		'tel',
	);
  if( in_array( $field['name'], $fields_hidden ) ) return false;

	return $field;
}
add_filter('acf/load_field', 'bs_case_study_open_gov_hide_fields', 20);

// Name of public official field
function bs_case_study_form_name_public_official($field) {
	$field['conditional_logic'] = 0;
	$field['label'] = 'Name';
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae73601af4fa', 'bs_case_study_form_name_public_official', 20 );

// Name of their organisation field
function bs_case_study_form_name_organisation($field) {
	$field['conditional_logic'] = 0;
	$field['label'] = 'Name of Organisation';
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae7360eaf4fb', 'bs_case_study_form_name_organisation', 20 );

// Their email address field
function bs_case_study_form_email_address($field) {
	$field['conditional_logic'] = 0;
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae7360eaf4fb', 'bs_case_study_form_email_address', 20 );

// Organisation name field
function bs_case_study_form_organisation_name($field) {
	$field['label'] = 'Organisation Name &amp; background (NGO, private company etc)';
	return $field;
}
add_filter( 'acf/load_field/key=field_5b34dd534345a', 'bs_case_study_form_organisation_name', 20 );

// Level of government field
function bs_case_study_form_level_of_government($field) {
	$field['label'] = 'Level of Government / Organisation';
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae73ab6c5ac5', 'bs_case_study_form_level_of_government', 20 );

// Lesson learned field
function bs_case_study_form_lesson_learned($field) {
	$field['instructions'] = 'What lessons from your experience would you like to share with others like you? Where there any pitfalls to avoid (maximum 4000 characters)?';
	$field['maxlength'] = 4000;
	return $field;
}
add_filter( 'acf/load_field/key=field_5ae78006a1e79', 'bs_case_study_form_lesson_learned', 20 );


	acf_form_head();
	get_header();

    global $post, $bp;

	if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 && !can_edit_acf_form( intval( $_GET['edit'] ) ) ) {
		?>
		<div class="col-sm-12">
			<div class="alert alert-warning text-center">
				<h3><?php echo __( 'Sorry, you cannot edit a case study that was submitted by someone else or a case study that has already been published. If you need to make changes to a published case study, please contact the OPSI team at', 'opsi' ); ?> <a href="mailto:opsi@oecd.org">opsi@oecd.org</a></h3>
			</div>

			<br />
			<a href="<?php echo $bp->loggedin_user->domain . 'innovations/'; ?>" title="<?php echo __( 'Back', 'opsi' ); ?>" class="button btn btn-default flipicon">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>  <?php echo __( 'Back', 'opsi' ); ?>
			</a>

		</div>
		<?php
		get_footer();
		return;
	}


	if ( isset( $_GET['delete'] ) && intval( $_GET['delete'] ) > 0 ) {

		$can_delete_cs = can_delete_cs( intval( $_GET['delete'] ) );

		if ( !can_delete_cs( intval( $_GET['delete'] ) ) ) {
			?>
			<div class="col-sm-12">
				<div class="alert alert-warning text-center">
					<h3><strong>Error!</strong> <?php echo __( 'You can not delete this case study.', 'opsi' ); ?></h3>
				</div>
			</div>
			<?php
			get_footer();
			return;
		} else {
			if ( isset( $_GET['confirm'] ) && intval( $_GET['confirm'] ) == 1 ) {

				if ( $can_delete_cs == 'delete' ) {
					wp_delete_post( intval( $_GET['delete'] ) );
				}

				if ( $can_delete_cs == 'request' ) {
					 wp_update_post( array( 'ID' => intval( $_GET['delete'] ), 'post_status' => 'pending_deletion' ) );
				}
				wp_safe_redirect( get_bloginfo('url') . '/members/'. $current_user->user_login . '/profile/');
				exit();

			} else {
			?>
				<div class="col-sm-12">
					<div class="alert alert-warning text-center">
						<h3><a href="<?php echo get_permalink( $post->ID ); ?>?delete=<?php echo intval( $_GET['delete'] ); ?>&confirm=1"><?php echo __( 'Please confirm deleting this case study by clicking here.', 'opsi' ); ?></a></h3>
					</div>
				</div>
			<?php
			}
		}
		get_footer();
		return;
	}

    $has_sidebar = 0;
    $layout = get_post_meta($post->ID, 'layout', true);
    if($layout != 'fullpage' && is_active_sidebar( 'sidebar' )) {
      $has_sidebar = 3;
    }

  ?>


	<div class="col-sm-3 dont-col-sm-push--9">
		<ul id="acf_steps">
		</ul>

		<?php if ( is_active_sidebar( 'casestudyformsidebar' )) { dynamic_sidebar( 'casestudyformsidebar' ); } ?>
	</div>
    <div class="col-sm-9 dont-col-sm-pull--3">

  <?php while ( have_posts() ) : the_post(); $postid = get_the_ID(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="formtop"></div>
			<div id="case_form" class="stepform">
            <?php

				$group = get_page_by_title('Case Studies', OBJECT, 'acf-field-group');

				$form_params = array(
					'field_groups' 	=> array( $group->ID ),
					'new_post'		=> array(
						'post_type'		=> 'case',
						'post_status'	=> 'draft',
						'post_author'	=> get_current_user_id(),
						'post_content' => true,
						'post_title' => true,
					),
					'submit_value'		=> __( 'Create a new case study', 'opsi' ),
					'post_id'			=> 'new_post',
					'form' 				=> true,
					'updated_message' 	=> '<span class="alert alert-success updatedalert" role="alert">'. __("Innovation submission saved on", 'acf') .' '. date_i18n( get_option('date_format') .' '.get_option('time_format') ) .'</span>',
					//'return' => '',
					'html_before_fields' => '<input type="hidden" id="csf_action" name="csf_action" value="" style="display:none;"><input type="hidden" id="form_step_field" name="form_step" value="0" style="display:none;">',
				);

				if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 ) {

					$form_params['post_id'] 		= $_GET['edit'];
					$form_params['new_post'] 		= false;
					$form_params['submit_value'] 	= __( 'Save your case study', 'opsi' );


				}

				acf_form( $form_params );


			?>
			</div>
          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>

	  <?php echo get_options_acf_fields_by_group_key(); ?>

    </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>
