<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe//extras
 */

if ( ! function_exists( 'hoe__boe' ) ) {

  add_action( 'wp_ajax_hoe__boe', 'hoe__boe' );
  add_action( 'wp_ajax_nopriv_hoe__boe', 'hoe__boe' );

  function hoe__boe($url=null) {
    check_ajax_referer( 'hoeboe__ajax__nonce', 'security' );
    $url = sanitize_url( $_GET["url"] );
    if ($url) :
      $response = wp_remote_get( $url );
      $code = wp_remote_retrieve_response_code( $response );
      echo 'hoeboe: ' . $code;
    endif;
    wp_die();
  }

}
