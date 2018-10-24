<?php
/*
Template Name: Case study form
Template Post Type: page
*/

/*
** ACF filter to customize Open Government form
*/
// give value to case type taxonomy field
function bs_case_study_form_taxonomy_field($field) {
	$field['value'] = 890;
  return $field;
}
add_filter('acf/prepare_field/key=field_5bc60b5d239b4', 'bs_case_study_form_taxonomy_field', 20);

// Hide Organisation Type field
function bs_case_study_form_organisation_type($field) {
  return;
}
add_filter('acf/prepare_field/key=field_5bc9fb9ae7390', 'bs_case_study_form_organisation_type', 20);

// Hide Part of Open Gov partnership field
function bs_case_study_form_open_gov_partnership($field) {
  return;
}
add_filter('acf/prepare_field/key=field_5bca000811cdb', 'bs_case_study_form_open_gov_partnership', 20);

// Innovation tags field
function bs_case_study_form_innovation_tag( $args, $field, $post_id ) {

  // modify args
  $args['meta_query'] = array(
		'relation'		=> 'AND',
		array(
			'key'			=> 'belonging_case_study',
			'value'			=> 'opsi',
			'compare'		=> '=='
		)
	);

  // return
  return $args;
}
add_filter('acf/fields/taxonomy/query/key=field_5ae779ff224b6', 'bs_case_study_form_innovation_tag', 20, 3);

	acf_form_head();
	get_header();

	?> <style>#acf-field_5ae7ab3b5dd80-, .acf-field-5bc60b5d239b4 {display: none;}</style> <?php

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
