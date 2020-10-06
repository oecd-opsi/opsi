<?php

add_action(  'login_init', 'auth0_login_redirect'  );

function auth0_login_redirect () {
    if( ! is_user_logged_in() ) {
        wp_redirect( '/login?redirected=1' );
        exit;
    }
}
