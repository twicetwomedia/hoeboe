<?php
/**
 * //hoe//boe//magic
 */

class Hoeboe {

    public function __construct() {}

    public function hoeboe__value($transient=null) {
        $value = null;
        if ($transient) : 
            $value = get_option('_transient_' . $transient);
        endif;
        return $value;
    }

    private function hoeboe__get($expiration=60) {
        $toggle = false;
        if ( $_GET["hoeboe"] ) :
            $hoeboe = $_GET["hoeboe"];
            if ( 'refresh' == $hoeboe ) : 
                if ( $_GET["hbchck"] ) {
                    $hbchck = $_GET["hbchck"];
                    $hb_transient_name = 'hb_' . $hbchck;
                    $hb_transient_val = get_transient($hb_transient_name);
                    $hb_key = get_option('Hoe_Boe')['_key'];
                    if ( ($hb_transient_val) && ($hb_key) && ($hb_transient_val == $hb_key) ) { 
                        $new_val = substr(str_shuffle(MD5(microtime())), 0, 18);
                        set_transient( $hb_transient_name, $new_val, $expiration );
                        $toggle = true;
                    }
                }
            endif;
        endif;
        return $toggle;
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
                $refresh_now = $this->hoeboe__get($expiration);
                if ($refresh_now) : 
                    if ($function) : 
                        if ( function_exists($function) ) :
                            $data = call_user_func_array($function, $parameters);
                            set_transient( $transient, $data, $expiration );
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

                $hoeboe_ref_private = get_option('Hoe_Boe')['_key'] ?: '';
                $hoeboe_ref_public  = wp_create_nonce('hoeboe-ref-public');
                $hb_temp_transient  = 'hb_' . $hoeboe_ref_public;
                if ( false === get_transient($hb_temp_transient) ) {
                    set_transient( $hb_temp_transient , $hoeboe_ref_private, $expiration );
                }
                $url__to__get = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "?hoeboe=refresh&hbchck=" . $hoeboe_ref_public;
                $needs__refresh = true;

            }
            
        }

        $return_array['url__to__get'] = $url__to__get;
        $return_array['needs__refresh'] = $needs__refresh;

        return $return_array;

    }

    public function hoeboe__ajax($refresh=false, $url=null) {
        if ( ($refresh) && ($url) && (filter_var($url, FILTER_VALIDATE_URL)) ) :
?>
    <script>
    jQuery(document).ready(function($) {
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
    });
    </script>
<?php
        endif;
    }

}

