<?php
/**
 * //hoe//boe//init
 */

function Hoeboe_init($file) {

  require_once( HOEBOE_PATH_INC . 'Hoe_Boe.php' );
  $hoeboe_plugin = new Hoe_Boe();

  if ( ! $hoeboe_plugin->isInstalled() ) {
      $hoeboe_plugin->install();
  }
  else {
      $hoeboe_plugin->upgrade();
  }

  $hoeboe_plugin->addActionsAndFilters();

  if ( ! $file ) {
      $file = __FILE__;
  }
  
  register_activation_hook($file, array(&$hoeboe_plugin, 'activate'));
  register_deactivation_hook($file, array(&$hoeboe_plugin, 'deactivate'));

  require_once( HOEBOE_PATH_INC . 'Hoeboe_Extras.php' );

  $hb_toggle = isset(get_option('Hoe_Boe')['toggle']) ? get_option('Hoe_Boe')['toggle'] : 'on';
  if ( ($hb_toggle) && ($hb_toggle == 'on') ) {
    require_once( HOEBOE_PATH_INC . 'Hoeboe_Advanced.php' );
  } else {
    require_once( HOEBOE_PATH_INC . 'Hoeboe_Basic.php' );  
  }

}
