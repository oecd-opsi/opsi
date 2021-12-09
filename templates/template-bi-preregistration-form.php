<?php
/*
Template Name: BI pre-registration form
Template Post Type: page
*/

/*
** ACF filter to customize BI pre-registration form
*/
// give value to status taxonomy field
function bs_bi_project_form_status_field($field) {
	// get Pre-registration term ID
	$status_term = get_term_by( 'slug', 'pre-registration', 'bi-project-status' );
	$status_term_id = $status_term->term_id;
	// set value
	$field['value'] = $status_term_id;
  return $field;
}
add_filter('acf/prepare_field/key=field_608047a8deef6', 'bs_bi_project_form_status_field', 20);

// Retrieve Intro page content
function bs_bi_form_first_page( $field ) {

	$bi_first_page = get_field( 'bi_pre-registration_first_page', 'option' );

	if ( !is_admin() && !empty( $bi_first_page ) && is_page( $bi_first_page ) ) {
		$field['label'] 	= '';
		$field['message'] 	= apply_filters( 'the_content', get_post_field( 'post_content', get_field( 'bi_pre-registration_first_page', 'option' ) ) );
	}

	return $field;
}
add_filter( "acf/load_field/key=field_60a38051279c8", 'bs_bi_form_first_page' );

// Hide results field
function bs_bi_hide_results($field) {
	return;
}
add_filter( "acf/prepare_field/key=field_60da2bd0d59e8", 'bs_bi_hide_results' );

// Hide Final report fields
function bs_bi_hide_final_report($field) {
	return;
}
add_filter( "acf/prepare_field/key=field_60a911bd5c04a", 'bs_bi_hide_final_report' );
add_filter( "acf/prepare_field/key=field_60a911e85c04b", 'bs_bi_hide_final_report' );

// Hide Additional Information group
function bs_bi_hide_additional_information_group($field) {
	return;
}
add_filter( "acf/prepare_field/key=field_60a91419e84bb", 'bs_bi_hide_additional_information_group' );

// Add Save buttons
function gen_info_save_btn($field) {
	$field['message'] = '<div class="col-md-12 layout_hero_block ">
	<div class="hb_inner text-left">Using the “save” option will save the data you have entered and allow you to return to data entry immediately or at a later time. You can click on previously completed sections in the sidebar to navigate back to them in order if you wish to revise your entry. If you have any problems, please contact us at <a href="mailto:opsi@oecd.org" title="contact OPSI">opsi@oecd.org</a> (include a screenshot if possible).
	<div class="text-center inlinep removebr formbuttons">
	<a class="button btn btn-default big goback" title="Back" href="#step-0"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a><a class="button btn btn-info big saveform" title="Save" href="#step-1">Save <i class="fa fa-floppy-o" aria-hidden="true"></i></a><a class="button btn btn-default big gonext" title="Next" href="#step-2">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>';
	return $field;
}
add_filter( 'acf/load_field/key=field_607f402365f67', 'gen_info_save_btn' );

function methods_save_btn($field) {
	$field['message'] = '<div class="col-md-12 layout_hero_block ">
	<div class="hb_inner text-left">Using the “save” option will save the data you have entered and allow you to return to data entry immediately or at a later time. You can click on previously completed sections in the sidebar to navigate back to them in order if you wish to revise your entry. If you have any problems, please contact us at <a href="mailto:opsi@oecd.org" title="contact OPSI">opsi@oecd.org</a> (include a screenshot if possible).
	<div class="text-center inlinep removebr formbuttons">
	<a class="button btn btn-default big goback" title="Back" href="#step-1"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a><a class="button btn btn-info big saveform" title="Save" href="#step-2">Save <i class="fa fa-floppy-o" aria-hidden="true"></i></a><a class="button btn btn-default big gonext" title="Next" href="#step-3">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>';
	return $field;
}
add_filter( 'acf/load_field/key=field_607f41bc5f055', 'methods_save_btn' );

function analysis_plan_save_btn($field) {
	$field['message'] = '<div class="col-md-12 layout_hero_block ">
	<div class="hb_inner text-left">Using the “save” option will save the data you have entered and allow you to return to data entry immediately or at a later time. You can click on previously completed sections in the sidebar to navigate back to them in order if you wish to revise your entry. If you have any problems, please contact us at <a href="mailto:opsi@oecd.org" title="contact OPSI">opsi@oecd.org</a> (include a screenshot if possible).
	<div class="text-center inlinep removebr formbuttons">
	<a class="button btn btn-default big goback" title="Back" href="#step-2"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</a><a class="button btn btn-info big saveform" title="Save" href="#step-3">Save <i class="fa fa-floppy-o" aria-hidden="true"></i></a><a class="button btn btn-default big gonext" title="Next" href="#step-4">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
	</div>
	</div>
	</div>';
	return $field;
}
add_filter( 'acf/load_field/key=field_607f40a865f68', 'analysis_plan_save_btn' );

/*
** Start the template
*/
acf_form_head();
get_header();

global $post, $bp;

// if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 && !can_edit_acf_form( intval( $_GET['edit'] ) ) ) {
if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 ) {
	?>
	<!-- <div class="col-sm-12">
		<div class="alert alert-warning text-center">
			<h3><?php // echo __( 'Sorry, you cannot edit a project that was submitted by someone else or a project that has already been published. If you need to make changes to a published project, please contact the OPSI team at', 'opsi' ); ?> <a href="mailto:opsi@oecd.org">opsi@oecd.org</a></h3>
		</div>

	</div> -->
	<?php
	// get_footer();
	// return;
	$post = array( 'ID' => $_GET['edit'], 'post_status' => 'draft' );
	wp_update_post($post);
}

if ( isset( $_GET['delete'] ) && intval( $_GET['delete'] ) > 0 ) {

	$can_delete_cs = can_delete_cs( intval( $_GET['delete'] ) );

	if ( !can_delete_cs( intval( $_GET['delete'] ) ) ) {
		?>
		<div class="col-sm-12">
			<div class="alert alert-warning text-center">
				<h3><strong>Error!</strong> <?php echo __( 'You can not delete this project.', 'opsi' ); ?></h3>
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
			$current_user = wp_get_current_user();
			wp_safe_redirect( get_bloginfo('url') . '/members/'. $current_user->user_nicename . '/profile/');
			exit();

		} else {
		?>
			<div class="col-sm-12">
				<div class="alert alert-warning text-center">
					<h3><a href="<?php echo get_permalink( $post->ID ); ?>?delete=<?php echo intval( $_GET['delete'] ); ?>&confirm=1"><?php echo __( 'Please confirm deleting this project by clicking here.', 'opsi' ); ?></a></h3>
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


<div class="col-sm-3 dont-col-sm-push--9 sidewrap">
	<ul id="acf_steps">
	</ul>

	<?php //if ( is_active_sidebar( 'casestudyformsidebar' )) { dynamic_sidebar( 'casestudyformsidebar' ); } ?>
</div>
  <div class="col-sm-9 dont-col-sm-pull--3">

	<?php while ( have_posts() ) : the_post(); ?>
	  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="formtop"></div>
			<div id="case_form" class="stepform">
	      <?php

				$group = get_page_by_title( 'BI Projects', OBJECT, 'acf-field-group' );

				$form_params = array(
					'id' => 'bi-project-form',
					'post_id'			=> 'new_post',
					'field_groups'       => array( $group->ID ),
					'new_post'		=> array(
						'post_type'		=> 'bi-project',
						'post_status'	=> 'draft',
						'post_author'	=> get_current_user_id(),
						'post_content' => true,
						'post_title' => true,
					),
					'submit_value'		=> __( 'Create a new pre-registration', 'opsi' ),
					'form' 				=> true,
					'updated_message' 	=> '<span class="alert alert-success updatedalert" role="alert">'. __("Pre-registration saved on", 'acf') .' '. date_i18n( get_option('date_format') .' '.get_option('time_format') ) .'. Thank you for submitting your application. Our team will carefully review it to ensure it meets the criteria to be featured on the OECD map. We thank you for your patience in the meantime.</span>',
					// 'return' => '',
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
	<?php endwhile; ?>

	<?php //echo get_options_acf_fields_by_group_key( 'group_597ebdb66a7e1', 'csform_example' ); ?>

</div>

<?php wp_reset_query(); ?>

<?php get_footer(); ?>
