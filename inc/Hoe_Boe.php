<?php
if ( ! defined('ABSPATH') ) { exit; }
/**
 * //hoe//boe
 */

include_once( 'Hoeboe_LifeCycle.php' );

class Hoe_Boe extends Hoeboe_LifeCycle {

  public function getOptionMetaData() {
    return array(
      'toggle' => array(__('Turn HoeBoe On/Off', 'hoeboe'), 'on', 'off')
    );
  }

  public function getPluginDisplayName() {
    return 'HoeBoe';
  }

  protected function getMainPluginFileName() {
    return 'hoeboe.php';
  }

  protected function getPluginDir() {
    $name = dirname(__FILE__);
    if ( strpos($name, '/inc') !== false ) {
      $name = str_replace('/inc', '', $name);
    }
    return $name;
  }

  public function upgrade() {
  }

  public function check_for_jquery() {
    if ( ! wp_script_is( 'jquery', 'registered' ) ) {
       wp_enqueue_script( 'jquery' );
    }
  }

  public function hoeboe_add_settings_link( $links ) {
    $settings_link = '<a href="tools.php?page=' . $this->getSettingsSlug() . '">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
  }

  protected function addSettingsSubMenuPageNav() {
    $displayName = $this->getPluginDisplayName();
    add_management_page(
      $displayName,
      $displayName,
      'manage_options',
      $this->getSettingsSlug(),
      array(&$this, 'settingsPage')
    );
  }

  public function addActionsAndFilters() {
    add_action( 'admin_menu', array(&$this, 'addSettingsSubMenuPage') );
    add_action( 'init', array(&$this, 'check_for_jquery') );
    add_filter( 'plugin_action_links_' . HOEBOE_BASENAME, array(&$this, 'hoeboe_add_settings_link') );
  } 

  public function settingsPage() {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.', 'hoeboe'));
    }

    $optionMetaData = $this->getOptionMetaData();

    if ($optionMetaData != null) {
      foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
        if (isset($_POST[$aOptionKey])) {
          $this->updateOption($aOptionKey, $_POST[$aOptionKey]);
        }
      }
    }

    $settingsGroup = get_class($this) . '-settings-group';
    ?>
      <div id="hoeboe-settings">
        <h2><?php echo $this->getPluginDisplayName() . ' '; _e('Settings', 'hoeboe'); ?></h2>
        <form method="post" action="">
        <?php settings_fields($settingsGroup); ?>
            <table class="form-table"><tbody>
            <?php
            if ($optionMetaData != null) {
              foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                $displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
                ?>
                  <tr valign="top">
                    <td width="150"><p><label for="<?php echo $aOptionKey ?>"><?php echo $displayText ?></label></p></td>
                    <td>
                    <?php $this->createFormControl($aOptionKey, $aOptionMeta, $this->getOption($aOptionKey)); ?>
                    </td>
                  </tr>
                <?php
              }
            }
            ?>
            <tr valign="top">
              <td colspan="2"><p>Note: Turning Hoeboe to "off" will allow your site to handle transients in a standard (non-AJAX) way while still using Hoeboe syntax.</p></td>
            </tr>
            </tbody></table>
            <p class="submit">
              <input type="submit" class="button-primary"
                       value="<?php _e('Save Changes', 'hoeboe') ?>"/>
            </p>
        </form>
        <img src="<?php echo str_replace('inc/', '', plugin_dir_url( __FILE__ )) . 'assets/img/hoeboe.png'; ?>" style="float:right;margin: -2.5em 2.5em 2.5em .1em;">
        <br />
        <p style="font-size:1.1em;"><a href="<?php echo esc_url( 'https://twicetwomedia.com/wordpress-plugins/#hoeboe' ); ?>" target="_blank" rel="noopener">HoeBoe documentation</a></p>
    </div>
    <?php

  }

}
