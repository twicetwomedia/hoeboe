<?php
/**
 * //hoe//boe//extras
 */

// ++ settings link from plugin list page
function hoeboe_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=Hoe_Boe_Settings">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . $Hoeboe_basename, 'hoeboe_add_settings_link' );

// ++ wp_ajax
add_action( 'wp_ajax_hoe__boe', 'hoe__boe' );
add_action( 'wp_ajax_nopriv_hoe__boe', 'hoe__boe' );
function hoe__boe($url=null) {
  check_ajax_referer( 'hoeboe__ajax__nonce', 'security' );
  $url = $_GET["url"];
  if ($url) :
    $response = wp_remote_get( $url );
    $code = wp_remote_retrieve_response_code( $response );
    echo 'hoeboe: ' . $code;
  endif;
  die();
}

