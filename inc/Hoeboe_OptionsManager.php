<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe//options
 */

class Hoeboe_OptionsManager {

  public function getOptionName() {
    return get_class( $this );
  }

  public function getOptionMetaData() {
    return array();
  }

  public function getOptionNames() {
    return array_keys($this->getOptionMetaData());
  }

  protected function deleteSavedOptions() {
    $optionMetaData = $this->getOptionMetaData();
    if ( is_array( $optionMetaData ) ) {
      $options = get_option( $this->getOptionName() );
      if ( ! is_array( $options ) )
        $options = array();
      foreach ( $optionMetaData as $aOptionKey => $aOptionMeta ) {
        if ( isset( $options[$aOptionKey] ) ) {
          unset( $options[$aOptionKey] );
        }
      }
      update_option( $this->getOptionName(), $options );
    }
  }

  public function getPluginDisplayName() {
    return get_class($this);
  }

  public function getOption( $optionName, $default = null ) {
    $options = get_option( $this->getOptionName() );
    if ( ! is_array( $options ) )
      $options = array();

    if ( isset( $options[$optionName] ) ) {
      $retVal = $options[$optionName];
    } elseif ( $default ) {
      $retVal = $default;
    } else {
      $retVal = '';
    }
    return $retVal;
  }

  public function deleteOption( $optionName ) {
    $options = get_option( $this->getOptionName() );
    if ( ! is_array( $options ) )
      $options = array();
    if ( isset( $options[$optionName] ) ) {
      unset( $options[$optionName] );
      return update_option( $this->getOptionName(), $options );
    } else {
      return true;
    }
  }

  public function addOption( $optionName, $value ) {
    return $this->updateOption( $optionName, $value );
  }

  public function updateOption( $optionName, $value ) {
    $options = get_option( $this->getOptionName() );
    if ( ! is_array( $options ) )
      $options = array();
    $options[$optionName] = $value;
    return update_option( $this->getOptionName(), $options );
  }

  public function getRoleOption($optionName) {
    $roleAllowed = $this->getOption($optionName);
    if (!$roleAllowed || $roleAllowed == '') {
      $roleAllowed = 'Administrator';
    }
    return $roleAllowed;
  }

  protected function roleToCapability($roleName) {
    switch ($roleName) {
      case 'Super Admin':
        return 'manage_options';
      case 'Administrator':
        return 'manage_options';
      case 'Editor':
        return 'publish_pages';
      case 'Author':
        return 'publish_posts';
      case 'Contributor':
        return 'edit_posts';
      case 'Subscriber':
        return 'read';
      case 'Anyone':
        return 'read';
    }
    return '';
  }

  public function isUserRoleEqualOrBetterThan($roleName) {
    if ('Anyone' == $roleName) {
      return true;
    }
    $capability = $this->roleToCapability($roleName);
    return current_user_can($capability);
  }

  public function canUserDoRoleOption($optionName) {
    $roleAllowed = $this->getRoleOption($optionName);
    if ('Anyone' == $roleAllowed) {
      return true;
    }
    return $this->isUserRoleEqualOrBetterThan($roleAllowed);
  }

  protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue) {
    if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) { // Drop-down list
      $choices = array_slice($aOptionMeta, 1);
      ?>
      <p><select name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>">
      <?php
        foreach ($choices as $aChoice) {
          $selected = ($aChoice == $savedOptionValue) ? 'selected' : '';
          ?>
              <option value="<?php echo $aChoice ?>" <?php echo $selected ?>><?php echo $this->getOptionValueI18nString($aChoice) ?></option>
          <?php
        }
      ?>
      </select></p>
      <?php

    } else { 
      ?>
      <p><input type="text" name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>"
                value="<?php echo esc_attr($savedOptionValue) ?>" size="50"/></p>
      <?php

    }
  }

  protected function getOptionValueI18nString($optionValue) {
    switch ($optionValue) {
      case 'true':
        return __('true', '1');
      case 'false':
        return __('false', '0');
      case 'on':
        return __('on', '1');
      case 'off':
        return __('off', '0');
    }
    return $optionValue;
  }

  protected function getMySqlVersion() {
    global $wpdb;
    $rows = $wpdb->get_results('select version() as mysqlversion');
    if (!empty($rows)) {
      return $rows[0]->mysqlversion;
    }
    return false;
  }

  public function registerSettings() {
    $settingsGroup = get_class($this) . '-settings-group';
    $optionMetaData = $this->getOptionMetaData();
    foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
        register_setting($settingsGroup, $aOptionMeta);
    }
  }

  public function settingsPage() {
  }

}

