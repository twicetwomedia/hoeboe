<?php
/*
   Plugin Name: Hoeboe
   Plugin URI: https://twicetwomedia.com/wordpress-plugins/
   Description: Easily update WordPress transients in the background via AJAX to increase site speed and avoid long page load times. Hoeboe can be especially helpful with caching of large external API calls or heavy internal database queries.
   Version: 0.1.2
   Author: twicetwomedia
   Author URI: https://twicetwomedia.com
   Text Domain: hoeboe
   License: GPLv3
  */

$Hoeboe_minReqPhpV = '5.3';
$Hoeboe_basename   = plugin_basename( __FILE__ );
$Hoeboe_path       = plugin_dir_path( __FILE__ );

// check PHP version
function Hoeboe_PhpVerWrong() {
    global $Hoeboe_minReqPhpV;
    echo '<div class="updated fade">' .
      __('Error: The plugin "Hoeboe" requires a newer version of PHP to be running.',  'hoeboe').
            '<br/>' . __('Minimum version of PHP required: ', 'hoeboe') . '<strong>' . $Hoeboe_minReqPhpV . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'hoeboe') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}
function Hoeboe_PhpVerCheck() {
    global $Hoeboe_minReqPhpV;
    if (version_compare(phpversion(), $Hoeboe_minReqPhpV) < 0) {
        add_action('admin_notices', 'Hoeboe_PhpVerWrong');
        return false;
    }
    return true;
}

//////////////////////////////////
// Run initialization
/////////////////////////////////

// run version check
if ( Hoeboe_PhpVerCheck() ) {
    include_once( $Hoeboe_path . 'hoeboe_init.php' );
    Hoeboe_init(__FILE__);
}

