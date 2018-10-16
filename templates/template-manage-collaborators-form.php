<?php 
/*
Template Name: Collaborators form
Template Post Type: page
*/

	acf_form_head();
	get_header();

    global $post;
	if ( !( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 ) ) {
		?>
		<div class="col-sm-12">
			<div class="alert alert-warning text-center">
				<h3><?php echo __( 'You do not have permission to access this page.', 'opsi' ); ?></h3>
			</div>
		</div>
		<?php
		return;
	}
	if ( isset( $_GET['edit'] ) && intval( $_GET['edit'] ) > 0 && !can_edit_acf_form( intval( $_GET['edit'] ), get_current_user_id(), array( 'any' ) ) ) {
		?>
		<div class="col-sm-12">
			<div class="alert alert-warning text-center">
				<h3><?php echo __( 'You do not have permission to access this page.', 'opsi' ); ?></h3>
			</div>
		</div>
		<?php
		get_footer();
		return;
	}
    
    $has_sidebar = 0;
    $layout = get_post_meta($post->ID, 'layout', true);
    if($layout != 'fullpage' && is_active_sidebar( 'sidebar' )) {
      $has_sidebar = 3;
    }
    
  ?>


	
    <div class="col-sm-12">

	<?php while ( have_posts() ) : the_post(); $postid = get_the_ID(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		  
			<?php if (get_field('hide_page_title') !== true) { ?>
              <h1 class="entry-title <?php echo (get_field('hide_social_sharing') === true ? 'nosocial' : ''); ?>"><?php the_title(); ?></h1>
              <?php } ?>
              <?php if (get_field('subheader') !='' ) { ?>
                <h2 class="entry-subtitle"><?php echo get_field('subheader'); ?></h2>
              <?php } ?>
              <div class="entry-content"><?php the_content(); ?></div>
		  
			<div id="formtop"></div>
			<div id="colab_form" class="colabform">
            <?php 
			
				$group = get_page_by_title('Case Study Collaborators', OBJECT, 'acf-field-group');
			
				$form_params = array(
					'field_groups' 		=> array( $group->ID ),
					'id' 				=> 'acf-colab-form',
					'post_id'			=> intval( $_GET['edit'] ),
					'submit_value'		=> __( 'Submit changes', 'opsi' ),
					'form' 				=> true,
					'html_submit_button'=> '<input type="submit" class="acf-button button button-primary btn btn-primary button-large" value="%s" />',
					'updated_message' 	=> '<span class="alert alert-success updatedalert" role="alert">'. __("The collaborators have been updated", 'acf') .'</span>',
					'html_before_fields'=> '<input type="hidden" id="cs_id" name="cs_id" value="'. intval( $_GET['edit'] ) .'" style="display:none;">',
				);
				
				acf_form( $form_params ); 
				
			
			?>
			</div>
			<p><br />
				<a href="<?php echo bp_core_get_user_domain( get_current_user_id() ); ?>innovations/" title="<?php echo __( 'My innovations', 'opsi' ); ?>" ><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> <?php echo __( 'Go back to your innovations list', 'opsi' ); ?></a>
			</p>
			
          </article>
      <?php // comments_template(); ?>
      <?php endwhile; ?>
      
    </div>

  <?php wp_reset_query(); ?>


<?php get_footer(); ?>