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
						'post_status'	=> 'draft',
						// 'post_author'	=> get_current_user_id(),
						'post_content' => true,
						'post_title' => true,
					),
					'submit_value'		=> __( 'Submit new pre-registration', 'opsi' ),
					'form' 				=> true,
					'updated_message' 	=> 'Your Pre-registration has been saved.',
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