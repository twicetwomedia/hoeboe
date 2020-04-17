<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe//lifecycle
 */

include_once( 'Hoeboe_InstallIndicator.php' );

class Hoeboe_LifeCycle extends Hoeboe_InstallIndicator {

  public function install() {
    $this->initOptions();
    $this->saveInstalledVersion();
    $this->markAsInstalled();
    $this->setRandomKey();
  }

  public function uninstall() {
    $this->deleteSavedOptions();
    $this->markAsUnInstalled();
  }

  public function upgrade() {
    $this->saveInstalledVersion();
  }

  public function activate() {  
    $this->saveInstalledVersion(); 
  }

  public function deactivate() {
    $this->saveInstalledVersion(); 
  }

  protected function initOptions() {
  }

  public function addActionsAndFilters() {
  }

  protected function requireExtraPluginFiles() {
  }

  protected function getSettingsSlug() {
    return get_class($this) . '_Settings';
  }

  public function addSettingsSubMenuPage() {
    $this->addSettingsSubMenuPageNav();
  }

  protected function addSettingsSubMenuPageNav() {
  }

}
