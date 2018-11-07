<?php acf_form_head(); ?>
<?php get_header('toolkits'); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<h1 class="entry-title "><?php the_title(); ?></h1>
	<?php the_content(); ?>

	<?php acf_form(array(
		'id' => 'toolkit-form',
		'post_id' => 'new_post',
		'new_post' => array(
			'post_type' => 'toolkit',
			'post_status' => 'pending',
			'post_author' => get_current_user_id(),
		),
		'post_title' => true,
		'post_content' => false,
		'submit_value' => __( 'Submit a new toolkit', 'opsi' ),
		'honeypot' => true,
		'fields' => array(
			'publisher',
			'url',
			'toolkit-type',
			'discipline-or-practice',
			'user-type',
			'license',
			'featured_image',
			'description',
			'email',
			'static_versions',
			'editable_versions'
		),
		'updated_message' 	=> sprintf( '<span class="alert alert-success updatedalert" role="alert">%s</span>', __( 'The toolkit has been saved.', 'opsi' ) ),
	)); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
