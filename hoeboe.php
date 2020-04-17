<?php
/*
   Plugin Name: Hoeboe
   Plugin URI: https://twicetwomedia.com/wordpress-plugins/
   Description: Easily update WordPress transients in the background via AJAX to increase site speed and avoid long page load times.
   Version: 0.1.4
   Author: twicetwomedia
   Author URI: https://twicetwomedia.com
   Text Domain: hoeboe
   License: GPLv3
  */

$hoeboe_version     = '0.1.4';
$hoeboe_name        = 'HoeBoe';
$hoeboe_min_php_v   = '5.3';
$hoeboe_file        = __FILE__;
$hoeboe_basename    = plugin_basename( $hoeboe_file );
$hoeboe_path_base   = plugin_dir_path( $hoeboe_file );
$hoeboe_path_inc    = $hoeboe_path_base . 'inc/';
$hoeboe_path_assets = $hoeboe_path_base . 'assets/';

defined( 'HOEBOE_VER' ) or define( 'HOEBOE_VER', $hoeboe_version );
defined( 'HOEBOE_NAME' ) or define( 'HOEBOE_NAME', $hoeboe_name );
defined( 'HOEBOE_PHPV' ) or define( 'HOEBOE_PHPV', $hoeboe_min_php_v );
defined( 'HOEBOE_FILE' ) or define( 'HOEBOE_FILE', $hoeboe_file );
defined( 'HOEBOE_BASENAME' ) or define( 'HOEBOE_BASENAME', $hoeboe_basename );
defined( 'HOEBOE_PATH_BASE' ) or define( 'HOEBOE_PATH_BASE', $hoeboe_path_base );
defined( 'HOEBOE_PATH_INC' ) or define( 'HOEBOE_PATH_INC', $hoeboe_path_inc );
defined( 'HOEBOE_PATH_ASSETS' ) or define( 'HOEBOE_PATH_ASSETS', $hoeboe_path_assets );

require_once( HOEBOE_PATH_INC . 'hoeboe__version_check.php' );
if ( hoeboe_php_ver_check() ) {
  include_once( HOEBOE_PATH_BASE . 'hoeboe_init.php' );
  hoeboe_init(__FILE__);
}
