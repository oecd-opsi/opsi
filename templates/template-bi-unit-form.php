<?php
/*
Template Name: BI Unit form
Template Post Type: page
*/

/*
** ACF filter to customize BI pre-registration form
*/
// Retrieve Intro page content
function bs_bi_form_first_page( $field ) {

	$bi_first_page = get_field( 'bi_unit_first_page', 'option' );

	if ( !is_admin() && !empty( $bi_first_page ) && is_page( $bi_first_page ) ) {
		$field['label'] 	= '';
		$field['message'] 	= apply_filters( 'the_content', get_post_field( 'post_content', get_field( 'bi_unit_first_page', 'option' ) ) );
	}

	return $field;
}
add_filter( "acf/load_field/key=field_60ad6024c03b0", 'bs_bi_form_first_page' );

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

				$group = get_page_by_title( 'BI Unit', OBJECT, 'acf-field-group' );

				$form_params = array(
					'id' => 'bi-unit-form',
					'post_id'			=> 'new_post',
					'field_groups'       => array( $group->ID ),
					'new_post'		=> array(
						'post_type'		=> 'bi-unit',
						'post_status'	=> 'draft',
						// 'post_author'	=> get_current_user_id(),
						'post_content' => true,
						'post_title' => true,
					),
					'submit_value'		=> __( 'Submit new unit', 'opsi' ),
					'form' 				=> true,
					'updated_message' 	=> '<span class="alert alert-success updatedalert" role="alert">Thank you for submitting your application. Our team will carefully review it to ensure it meets the criteria to be featured on the OECD map. We thank you for your patience in the meantime.</span>',
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
