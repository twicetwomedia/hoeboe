<?php
/**
 * //hoe//boe//init
 */

function Hoeboe_init($file) {

    require_once( 'inc/Hoe_Boe.php' );
    $hoeboe_plugin = new Hoe_Boe();

    // Install on first activation
    if (!$hoeboe_plugin->isInstalled()) {
        $hoeboe_plugin->install();
    }
    else {
        // Perform any upgrade activities prior to activation
        $hoeboe_plugin->upgrade();
    }

    // Add callbacks to hooks
    $hoeboe_plugin->addActionsAndFilters();

    if (!$file) {
        $file = __FILE__;
    }
    // Register the Plugin Activation Hook
    register_activation_hook($file, array(&$hoeboe_plugin, 'activate'));

    // Register the Plugin Deactivation Hook
    register_deactivation_hook($file, array(&$hoeboe_plugin, 'deactivate'));
}

include_once( 'inc/Hoeboe_Extras.php' );

