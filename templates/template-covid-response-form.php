<?php
/*
Template Name: Covid Response form
Template Post Type: page
*/

acf_form_head();
get_header();

global $post, $bp;

$has_sidebar = 0;
$layout      = get_post_meta( $post->ID, 'layout', true );
if ( $layout != 'fullpage' && is_active_sidebar( 'sidebar' ) ) {
	$has_sidebar = 3;
}

?>

<div class="col-md-12">
	<div class="single_img_wrap covid-banner">
		<img src="/wp-content/uploads/2020/04/OPSI-Covid19-Tracker-banner.jpg" class="attachment-blog size-blog wp-post-image" alt="OPSI COVID-19 Innovative Response Tracker">
	</div>
</div>

<div class="col-sm-3 dont-col-sm-push--9">
	<ul id="acf_steps">
	</ul>

	<?php if ( is_active_sidebar( 'sidebar_covid_response_form' ) ) {
		dynamic_sidebar( 'sidebar_covid_response_form' );
	} ?>
</div>
<div class="col-sm-9 dont-col-sm-pull--3">

	<?php while ( have_posts() ) : the_post();
		$postid = get_the_ID(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="formtop"></div>
			<div id="case_form" class="stepform">
				<?php

				$group = get_page_by_title( 'Covid Responses', OBJECT, 'acf-field-group' );

				$form_params = array(
					'id'                 => 'covid-response-form',
					'field_groups'       => array( $group->ID ),
					'new_post'           => array(
						'post_type'    => 'covid_response',
						'post_status'  => 'publish',
						'post_author'  => get_current_user_id(),
						'post_content' => true,
						'post_title'   => true,
					),
					'submit_value'       => __( 'Create a new Covid response', 'opsi' ),
					'post_id'            => 'new_post',
					'form'               => true,
					'uploader'           => 'basic',
					'updated_message'    => '<span class="alert alert-success updatedalert" role="alert">' . __( "Innovation submission saved on", 'acf' ) . ' ' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) . '</span>',
					//'return' => '',
					'html_before_fields' => '<input type="hidden" id="csf_action" name="csf_action" value="" style="display:none;"><input type="hidden" id="form_step_field" name="form_step" value="0" style="display:none;">',
				);

				acf_form( $form_params );

				?>
			</div>
		</article>
		<?php // comments_template(); ?>
	<?php endwhile; ?>

	<?php echo get_options_acf_fields_by_group_key( 'group_597ebdb66a7e1', 'covid_form_example' ); ?>

</div>

<?php wp_reset_query(); ?>

<?php get_footer(); ?>
