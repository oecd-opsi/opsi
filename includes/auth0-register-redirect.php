<?php

add_action('bp_core_signup_user', 'bp_register_redirect', 100, 1);

function bp_register_redirect($_user)
{
    $root_url = get_bloginfo('wpurl');
    $redirect_url = esc_url($_GET['redirect_uri']);

    wp_destroy_current_session();
    wp_clear_auth_cookie();
    wp_set_current_user( 0 );

    if ($_GET['redirect_uri']) {
        wp_redirect("{$root_url}/login?account_created=1&redirect_uri={$redirect_url}");
    } else {
        wp_redirect("{$root_url}/login?account_created=1");
    }
    die;
}
