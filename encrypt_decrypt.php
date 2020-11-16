<?php

function encrypt_decrypt($action, $string, $secret_key) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    //
    $secret_iv = 'This is a secret iv';
    // hash
    $key = hash('sha256', $secret_key); //sha256 is a hashing algorithm
    $iv = substr(hash('sha256', $secret_iv), 0, 16); // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning (iv = A non-NULL Initialization Vector.)
    if ( $action == 'encrypt' ) {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
?>