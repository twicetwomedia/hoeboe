<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe//indicators
 */

include_once( 'Hoeboe_OptionsManager.php' );

class Hoeboe_InstallIndicator extends Hoeboe_OptionsManager {

  const optionInstalled = '_installed';
  const optionVersion = '_version';
  const optionKey = '_key';

  public function isInstalled() {
  return $this->getOption(self::optionInstalled) == true;
  }

  protected function markAsInstalled() {
    return $this->updateOption(self::optionInstalled, true);
  }

  protected function markAsUnInstalled() {
    return $this->deleteOption(self::optionInstalled);
  }

  protected function getVersionSaved() {
    return $this->getOption(self::optionVersion);
  }

  protected function generateRandomString($length=18) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
  }

  protected function setRandomKey() {
    return $this->updateOption(self::optionKey, $this->generateRandomString());
  }

  protected function setVersionSaved($version) {
    return $this->updateOption(self::optionVersion, $version);
  }

  protected function getMainPluginFileName() {
    return basename(dirname(__FILE__)) . 'php';
  }

  protected function getPluginDir() {
    return dirname(__FILE__);
  }

  public function getPluginHeaderValue($key) {
    $data = file_get_contents($this->getPluginDir() . DIRECTORY_SEPARATOR . $this->getMainPluginFileName());
    $match = array();
    preg_match('/' . $key . ':\s*(\S+)/', $data, $match);
    if (count($match) >= 1) {
      return $match[1];
    }
    return null;
  }

  public function getVersion() {
    return $this->getPluginHeaderValue('Version');
  }

  public function isInstalledCodeAnUpgrade() {
    return $this->isSavedVersionLessThan($this->getVersion());
  }

  public function isSavedVersionLessThan($aVersion) {
    return $this->isVersionLessThan($this->getVersionSaved(), $aVersion);
  }

  public function isSavedVersionLessThanEqual($aVersion) {
    return $this->isVersionLessThanEqual($this->getVersionSaved(), $aVersion);
  }

  public function isVersionLessThanEqual($version1, $version2) {
    return (version_compare($version1, $version2) <= 0);
  }

  public function isVersionLessThan($version1, $version2) {
    return (version_compare($version1, $version2) < 0);
  }

  protected function saveInstalledVersion() {
    $this->setVersionSaved($this->getVersion());
  }

}
