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
            //'_version' => array('Installed Version'), // Uncomment to test upgrades.
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

/*
    public function activate() {
    }
*/
    
    public function addActionsAndFilters() {

        // ++ options administration page
        add_action( 'admin_menu', array(&$this, 'addSettingsSubMenuPage') );

    } 

}

$hb_toggle = get_option('Hoe_Boe')['toggle'];
if ( ($hb_toggle) && ($hb_toggle == 'on') ) {
    require_once( 'Hoeboe_Advanced.php' );
} else {
    include_once( 'Hoeboe_Basic.php' );  
}

