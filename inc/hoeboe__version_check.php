<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * version check
**/

if ( ! function_exists( 'hoeboe_php_ver_wrong' ) ) {

  function hoeboe_php_ver_wrong() {
    echo '<div class="updated fade">' .
      __('Error: The plugin "' . HOEBOE_NAME . '" requires a newer version of PHP.',  'hoeboe').
            '<br/>' . __('Minimum version of PHP required: ', 'hoeboe') . '<strong>' . HOEBOE_PHPV . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'hoeboe') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
  }

}

if ( ! function_exists( 'hoeboe_php_ver_check' ) ) {

  function hoeboe_php_ver_check() {
    if ( version_compare(phpversion(), HOEBOE_PHPV) < 0 ) {
      add_action('admin_notices', 'hoeboe_php_ver_wrong');
      return false;
    }
    return true;
  }

}
