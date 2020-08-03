<?php

add_action('bp_before_account_details_fields', 'add_auth0_form');

function add_auth0_form() {

    if ($_GET['redirect_uri']) {
        $redirect_uri = esc_url($_GET['redirect_uri']);
        echo do_shortcode( "<h2>Connect</h2>[auth0 redirect_to=\"$redirect_uri\"]" );
    } else {
        echo do_shortcode( "<h2>Connect</h2>[auth0]" );
    }
}
