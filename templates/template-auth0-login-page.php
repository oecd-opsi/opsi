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

function auth0_docs_hook_lock_options(array $options)
{
    $options['languageDictionary'] = [
        'title' => 'Log In'
    ];
    return $options;
}

add_filter('auth0_lock_options', 'auth0_docs_hook_lock_options', 10);

?>

<?php if ($_GET['account_created']) : ?>
    <div class="clearfix"></div>
    <div class="alert alert-success text-center">
        <p><?php _e('You have successfully created your account! We will notify you once your account has been approved. Until then, your account will have limited functionality, but you will still be able to submit case studies and use the Portfolio Exploration Tool. Please log in to start using your account.', 'buddypress'); ?></p>
        <!-- <h2><a href="<?php // echo get_permalink( get_page_by_path( 'case-study-form-open-government' ) ); ?>"><?php // _e( 'If your innovation is mostly related to Open Government, please use our specialised form here.', 'buddypress' ); ?></a></h2> -->
    </div>
<?php endif; ?>

<?php if ($_GET['redirected']) : ?>
    <div class="clearfix"></div>
    <div class="alert alert-success text-center">
        <p>Please log in to access this content. If you are not yet a member, you may register <a href="/register">here</a>.</p>
    </div>
<?php endif; ?>

<div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

    <div class="custom-auth0-info">
        The login process has recently changed. Please use you e-mail address (not your username) to log in.
    </div>

</div>

<div class="col-sm-<?php echo 12 - $has_sidebar; ?> <?php echo($has_sidebar > 0 ? 'col-sm-pull-3' : ''); ?>">

    <?php $redirect_uri = $_GET['redirect_uri'] ? esc_url($_GET['redirect_uri']) : get_option('siteurl') ?>

    <?php echo do_shortcode("[auth0 redirect_to=\"$redirect_uri\"]"); ?>

</div>

<?php wp_reset_query(); ?>


<?php get_footer(); ?>
