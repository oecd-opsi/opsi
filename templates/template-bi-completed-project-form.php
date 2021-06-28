<?php
/*
Template Name: BI completed project form
Template Post Type: page
*/

/*
** ACF filter to customize BI pre-registration form
*/
// give value to status taxonomy field
function bs_bi_project_form_status_field($field) {
	// get Pre-registration term ID
	$status_term = get_term_by( 'slug', 'completed', 'bi-project-status' );
	$status_term_id = $status_term->term_id;
	// set value
	$field['value'] = $status_term_id;
  return $field;
}
add_filter('acf/prepare_field/key=field_608047a8deef6', 'bs_bi_project_form_status_field', 20);

// Retrieve Intro page content
function bs_bi_form_first_page( $field ) {

	$bi_first_page = get_field( 'bi_completed_project_first_page', 'option' );

	if ( !is_admin() && !empty( $bi_first_page ) && is_page( $bi_first_page ) ) {
		$field['label'] 	= '';
		$field['message'] 	= apply_filters( 'the_content', get_post_field( 'post_content', get_field( 'bi_completed_project_first_page', 'option' ) ) );
	}

	return $field;
}
add_filter( "acf/load_field/key=field_60a38051279c8", 'bs_bi_form_first_page' );

// Hide Data Collection field
function bs_bi_project_form_data_collection($field) {
  return;
}
add_filter('acf/prepare_field/key=field_60a41f69545e1', 'bs_bi_project_form_data_collection', 20);

// Edit step 3 name
function bs_bi_step3_name($field) {
	$field['label'] 	= 'Detailed information';
	return $field;
}
add_filter( "acf/load_field/key=field_607ea64ec38e5", 'bs_bi_step3_name' );

// Edit step 3 heading
function bs_bi_step3_heading($field) {
	$field['message'] 	= '<h2>Detailed information</h2>';
	return $field;
}
add_filter( "acf/load_field/key=field_607ea66dc38e6", 'bs_bi_step3_heading' );

// Hide unuseful fields from step 3
function bs_bi_hide_step3_fields($field) {
	return;
}
add_filter( "acf/prepare_field/key=field_607ea799c38eb", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_607ea80ec38ee", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_607ea727c38e9", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_607ea87cc38f1", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a420f31fb78", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a421941fb7a", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a421c81fb7b", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a422e089c14", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a4231489c15", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a4234289c16", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a4236d89c17", 'bs_bi_hide_step3_fields' );
add_filter( "acf/prepare_field/key=field_60a423a689c18", 'bs_bi_hide_step3_fields' );

// Hide Embargo related fields
function bs_bi_hide_embargo_fields($field) {
	return;
}
add_filter( "acf/prepare_field/key=field_60a8178424bdf", 'bs_bi_hide_embargo_fields' );
add_filter( "acf/prepare_field/key=field_60a41eed6bb82", 'bs_bi_hide_embargo_fields' );
add_filter( "acf/prepare_field/key=field_60a4201e545e4", 'bs_bi_hide_embargo_fields' );
add_filter( "acf/prepare_field/key=field_60a9141ae84e2", 'bs_bi_hide_embargo_fields' );

// Hide Review and Collaboration group
function bs_bi_hide_review_collaboration_group($field) {
	return;
}
add_filter( "acf/prepare_field/key=field_607ef2e47e55c", 'bs_bi_hide_review_collaboration_group' );

/*
** Start the template
*/
acf_form_head();
get_header();

global $post, $bp;

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
						'post_status'	=> 'pending',
						// 'post_author'	=> get_current_user_id(),
						'post_content' => true,
						'post_title' => true,
					),
					'submit_value'		=> __( 'Submit new project', 'opsi' ),
					'form' 				=> true,
					'updated_message' 	=> 'Your project has been saved.',
					// 'return' => '',
				);

				acf_form( $form_params );
				?>
			</div>
	  </article>
	<?php endwhile; ?>

	<?php //echo get_options_acf_fields_by_group_key( 'group_597ebdb66a7e1', 'csform_example' ); ?>

</div>

<?php wp_reset_query(); ?>

<?php get_footer(); ?>
