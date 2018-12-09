<?php
/**
 * //hoe//boe
 */

include_once( 'Hoeboe_LifeCycle.php' );

class Hoe_Boe extends Hoeboe_LifeCycle {

    /**
     * @return array of option meta data
     */
    public function getOptionMetaData() {
        return array(
            'toggle' => array(__('Turn HoeBoe On/Off', 'hoeboe'), 'on', 'off')
        );
    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Hoeboe';
    }

    protected function getMainPluginFileName() {
        return 'hoeboe.php';
    }

    /**
     * Perform actions when upgrading from version X to version Y
     * @return void
     */
    public function upgrade() {
    }

    public function check_for_jquery() {
        if ( ! wp_script_is( 'jquery' ) ) {
            wp_enqueue_script( 'jquery-hb', plugins_url( 'assets/js/jquery.min.js', dirname(__FILE__) ) );
        }
    }

    public function addActionsAndFilters() {

        // ++ options administration page
        add_action( 'admin_menu', array(&$this, 'addSettingsSubMenuPage') );

        //enqueue jquery if not already present
        add_action( 'init', array(&$this, 'check_for_jquery') );

    } 

}

$hb_toggle = get_option('Hoe_Boe')['toggle'] ?: 'on';
if ( ($hb_toggle) && ($hb_toggle == 'on') ) {
    require_once( 'Hoeboe_Advanced.php' );
} else {
    require_once( 'Hoeboe_Basic.php' );  
}

