<?php

function password_validation() {
    global $bp;

    if ( !empty( $_POST['signup_password'] ) )
        $errors = password_errors( $_POST['signup_password'] );
        if ( !empty($errors) ){
            $bp->signup->errors['signup_password'] = $errors;
        }
}

add_action( 'bp_signup_validate', 'password_validation');

function password_errors($candidate) {
    $r1='/[A-Z]/';  //Uppercase
    $r2='/[a-z]/';  //lowercase
    $r3='/[!@#$%^&*()-_=+{};:,<.>]/';  // whatever you mean by special char
    $r4='/[0-9]/';  //numbers

    $errors = '';

    if(preg_match_all($r1,$candidate, $o)<1) $errors .= 'Password must contain at least 1 uppercase character (A-Z). ';
    if(preg_match_all($r2,$candidate, $o)<1) $errors .= 'Password must contain at least 1 lowercase character (a-z). ';
    if(preg_match_all($r3,$candidate, $o)<1) $errors .= 'Password must contain at least 1 special character (!@#..). ';
    if(preg_match_all($r4,$candidate, $o)<1) $errors .= 'Password must contain at least 1 number (0-9). ';
    if(strlen($candidate)<8) $errors .= 'Password needs to be at least 8 characters long. ';

    return $errors;
}
