<?php

add_action('bp_core_signup_user', 'bp_register_redirect', 100, 1);

function bp_register_redirect($_user)
{
    if ($_GET['redirect_uri']) {
        wp_destroy_current_session();
        wp_clear_auth_cookie();
        wp_set_current_user( 0 );
        $root_url = get_bloginfo('wpurl');
        wp_redirect("{$root_url}/login?redirect_uri={$_GET['redirect_uri']}");
        die;
    }
}
