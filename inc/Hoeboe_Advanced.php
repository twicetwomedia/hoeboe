<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe//advanced
 */

class Hoeboe {

  public function __construct() {}

  public function hoeboe__value($transient=null) {

    $value = null;

    if ($transient) : 
      $value = get_option('_transient_' . $transient) ?: get_option('_transient_' . $transient . '_bak');
    endif;

    return $value;
    
  }

  private function hoeboe__get() {

    $toggle = false;

    if ( isset( $_GET["hoeboe"] ) ) :
      $hoeboe = sanitize_text_field( $_GET["hoeboe"] );
      if ( 'refresh' == $hoeboe ) : 
        if ( isset( $_GET["hbchck"] ) ) {
          $hbchck = sanitize_text_field( $_GET["hbchck"] );
          $hb_transient_name = 'hb_' . $hbchck;
          $hb_transient_val = get_transient($hb_transient_name);
          $hb_key = get_option('Hoe_Boe')['_key'];
          if ( ($hb_transient_val) && ($hb_key) && ($hb_transient_val == $hb_key) ) { 
            $toggle = true;
          }
        }
      endif;
    endif;

    return $toggle;

  }

  private function hoeboe__check_expiry($transient=null, $data=null, $temp_expire=30) {

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
    
    $value = null;

    if ($transient) : 
      $value = $this->hoeboe__value($transient);
      $is_expired = $this->hoeboe__check_expiry($transient, $value);
      if ($is_expired) : 
        $refresh_now = $this->hoeboe__get();
        if ($refresh_now) : 
          if ($function) : 
            if ( function_exists($function) ) :
              $data = call_user_func_array($function, $parameters);
              set_transient( $transient, $data, $expiration );
              set_transient( $transient . '_bak', $data, $expiration );
            else :
              $result = 'function does not exist';
            endif;
          endif;
        else :
          $do__magic = $this->hoeboe__magic($is_expired, $transient, $refresh_now, $expiration);
          $needs__refresh = $do__magic['needs__refresh'];
          $url__to__get = $do__magic['url__to__get'];
          $do__ajax = $this->hoeboe__ajax($needs__refresh, $url__to__get);
        endif;
      endif;
    endif;

    return $value;

  }

  private function hoeboe__magic($expired=false, $transient=null, $toggle=false, $expiration=60) {

    $url__to__get = null;
    $needs__refresh = false;
    $return_array = array();

    if ($expired) {

      if ( ! $toggle ) {

        $hoeboe_ref_public  = wp_create_nonce('hoeboe-ref-public');
        $hb_temp_transient  = 'hb_' . $hoeboe_ref_public;
        $hb_ref_private = get_option('Hoe_Boe')['_key'] ?: '';
        $hb_temp_expiration = 60;
        if ( false === get_transient($hb_temp_transient) ) {
          set_transient( $hb_temp_transient , $hb_ref_private, $hb_temp_expiration );
        }
        $url__to__get = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "?hoeboe=refresh&hbchck=" . $hoeboe_ref_public;
        $needs__refresh = true;

      }
      
    }

    $return_array['url__to__get'] = $url__to__get;
    $return_array['needs__refresh'] = $needs__refresh;

    return $return_array;

  }

  public function hoeboe__ajax($refresh=false, $url=null, $random=null) {
    $random = rand(111111, 999999);
    if ( ($refresh) && ($url) && (filter_var($url, FILTER_VALIDATE_URL)) ) :
?>
  <script>
  jQuery(document).ready(function($) { function a<?php echo $random; ?>() {
    console.log('updating ...');
    var hoeboe_nonce = '<?php echo wp_create_nonce( "hoeboe__ajax__nonce" ); ?>';
    var hoeboe_url = '<?php echo $url; ?>';
    var data = {
        'action'  : 'hoe__boe',
        'security': hoeboe_nonce,
        'url'     : hoeboe_url
    };
    var ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
    jQuery.get(ajaxurl, data, function(response){ 
        console.log(response);
    });
  }
  a<?php echo $random; ?>();
  });
  </script>
<?php
    endif;
  }

}
