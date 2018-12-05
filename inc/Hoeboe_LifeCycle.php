<?php
/**
 * //hoe//boe//4ever
 */

include_once( 'Hoeboe_InstallIndicator.php' );

class Hoeboe_LifeCycle extends Hoeboe_InstallIndicator {

    public function install() {

        // Initialize plugin options
        $this->initOptions();

        // Record the installed version
        $this->saveInstalledVersion();

        // Avoid running install() more then once
        $this->markAsInstalled();

        // Set a key on install
        $this->setRandomKey();
        
    }

    public function uninstall() {
        $this->deleteSavedOptions();
        $this->markAsUnInstalled();
    }

    /**
     * @return void
     */
    public function upgrade() {
    }

    /**
     * @return void
     */
    public function activate() {   
    }

    /**
     * @return void
     */
    public function deactivate() {
    }

    /**
     * @return void
     */
    protected function initOptions() {
    }

    public function addActionsAndFilters() {
    }

    /**
     * @return void
     */
    public function addSettingsSubMenuPage() {
        $this->addSettingsSubMenuPageNav();
    }

    protected function requireExtraPluginFiles() {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }

    /**
     * @return string Slug name for the URL to the Setting page
     */
    protected function getSettingsSlug() {
        return get_class($this) . '_Settings';
    }

    protected function addSettingsSubMenuPageNav() {
        $this->requireExtraPluginFiles();
        $displayName = $this->getPluginDisplayName();
        add_options_page($displayName,
                         $displayName,
                         'manage_options',
                         $this->getSettingsSlug(),
                         array(&$this, 'settingsPage'));
    }

}

