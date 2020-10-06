<?php
/*
Template Name: Auth0 logout page
Template Post Type: page
*/

$redirect_uri = $_GET['redirect_uri'] ? esc_url($_GET['redirect_uri']) : get_option( 'siteurl' );
$auth0_domain = wp_auth0_get_option( 'domain' );
$auth0_uri = "https://{$auth0_domain}/v2/logout?returnTo=\"{$redirect_uri}\"";

wp_destroy_current_session();
wp_clear_auth_cookie();
wp_set_current_user( 0 );

wp_redirect($auth0_uri);
