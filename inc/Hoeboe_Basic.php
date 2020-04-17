<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe//basic
 */

/**
 * don't break WP Transients when Hoeboe is turned off in wp-admin
 */

class Hoeboe {

  public function hoeboe__value($transient=null) {
    $value = null;
    if ($transient) : 
      $value = get_option('_transient_' . $transient);
    endif;
    return $value;
  }

  private function hoeboe__check_expiry($transient=null) {
    $expired = true;
    if ($transient) : 
      $expired = false;
      if ( false === get_transient($transient) ) : 
        $expired = true;
      endif;
    endif;
    return $expired;
  }

  public function hoeboe__updatetransient($transient=null, $function=null, $parameters=array(), $expiration=60) {
    if ($transient) : 
      $value = $this->hoeboe__value($transient);
      $is_expired = $this->hoeboe__check_expiry($transient);
      if ($is_expired) : 
        if ($function) : 
          if ( function_exists($function) ) {
            $data = call_user_func_array($function, $parameters);
            if ($data) {
              set_transient( $transient, $data, $expiration );
              $value = $data;
            }
          }
        endif;
      endif;
    endif;
    return $value;
  }

}
