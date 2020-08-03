<?php
/*
Template Name: Auth0 login page
Template Post Type: page
*/

global $post;

$has_sidebar = 0;
$layout = get_post_meta($post->ID, 'layout', true);
get_header();

if (is_user_logged_in() && $_GET['redirect_uri']) {
    wp_safe_redirect($_GET['redirect_uri']);
}

function auth0_docs_hook_lock_options( array $options ) {
	$options['languageDictionary'] = [
        'title' => 'Log In'
    ];
	return $options;
}
add_filter( 'auth0_lock_options', 'auth0_docs_hook_lock_options', 10 );

?>

<div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo ($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

    <?php $redirect_uri = $_GET['redirect_uri'] ? esc_url($_GET['redirect_uri']) : get_option( 'siteurl' )?>

    <?php echo do_shortcode( "[auth0 redirect_to=\"$redirect_uri\"]" ); ?>

</div>

<?php wp_reset_query(); ?>


<?php get_footer(); ?>
