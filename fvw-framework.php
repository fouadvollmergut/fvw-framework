<?php

  /*
   * Plugin Name: Fouad Vollmer Framework
   * Description: Technisches Framework für WordPress-Webseiten. Grundlage für verschiedene Addon-Plugins.
   * Author: Fouad Vollmer
   * Author URI: https://fouadvollmer.de/
   * Text Domain: fvw-framework
   * Domain Path: /languages/
   * Version: x.x.x
  */


  /* PATH VARS */

  define( 'FVW_FRAMEWORK_BASE_PATH', plugin_dir_path( __FILE__ ) );
  define( 'FVW_FRAMEWORK_BASE_URL', plugin_dir_url( __FILE__ ) );


  /* ICLUDE UPDATER */

  /******************************* UPDATER *******************************/

	require_once FVW_FRAMEWORK_BASE_PATH . 'updater/plugin-update-checker.php';

	$updater = Puc_v4_Factory::buildUpdateChecker( 'https://update.fouadvollmer.de/plugin/fvw-framework/', __FILE__, 'fvw-framework' );


  /* INCLUDE CLASSES */

  require_once FVW_FRAMEWORK_BASE_PATH . 'classes/factory.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'classes/tools.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'classes/form.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'classes/field.php';


  /* INCLUDE FUNCTIONS */

  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/head.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/description.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/misc.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/mailer.php';

  /* INCLUDE PRIVACY OVERLAY */
  
  add_filter( 'fvw_add_privacy', 'fvw_load_privacy_overlay', 10, 1);
  
  function fvw_load_privacy_overlay( $privacy ) {
    if ($privacy) {
      require_once FVW_FRAMEWORK_BASE_PATH . 'includes/privacy.php';
    }
    
    return $privacy;
  }

  
  /* FACTORY FUNCTION */

  $fvw = new FVW_FACTORY;

  function fvw() {
    global $fvw;
    return $fvw;
  }


  /* STYLES AND SCRIPTS */

  // Frontend scripts and styles
  add_action( 'wp_enqueue_scripts', function() {

    // Load styles and scripts
    wp_enqueue_style( 'fvw-framework-style', plugins_url( '/build/main.css', __FILE__ ) );
    wp_enqueue_script( 'fvw-framework-script', plugins_url( '/build/main.js', __FILE__ ) );

    // Fontawesome
    if( apply_filters( 'fvw_load_fontawesome', true ) OR gdymc_logged() ):
      wp_enqueue_style( 'fontawesome-style', plugins_url( '/assets/addons/fontawesome/css/fontawesome.min.css', __FILE__ ), array() );
      wp_enqueue_style( 'fontawesome-solid', plugins_url( '/assets/addons/fontawesome/css/solid.min.css', __FILE__ ), array() );
      wp_enqueue_style( 'fontawesome-regular', plugins_url( '/assets/addons/fontawesome/css/regular.min.css', __FILE__ ), array() );
      wp_enqueue_style( 'fontawesome-light', plugins_url( '/assets/addons/fontawesome/css/light.min.css', __FILE__ ), array() );
      wp_enqueue_style( 'fontawesome-v5', plugins_url( '/assets/addons/fontawesome/css/v5-font-face.min.css', __FILE__ ), array() );
    endif;

    // Google Recaptcha
    if( $recaptcha_key = fvw()->setting( 'integration/google_recaptcha/key' ) AND $recaptcha_secret = fvw()->setting( 'integration/google_recaptcha/secret' ) ):
      wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptcha_key, '4.6.3' );
    endif;

    // Mobilemenu
    if( apply_filters( 'fvw_load_mobilemenu', true ) ):
      wp_enqueue_script( 'fvw-mobilemenu', plugins_url( '/assets/scripts/mobilemenu.min.js', __FILE__ ), array( 'jquery', 'fvw-framework-script' ) );
    endif;

    // Localization
    $timestamp = strtotime( 'next monday' );
    
    for( $i = 0; $i < 7; $i++ ):
      $dayNames[] = date_i18n( 'l', $timestamp );
      $dayNamesShort[] = date_i18n( 'D', $timestamp );
      $timestamp = strtotime('+1 day', $timestamp );
    endfor;

    array_unshift( $dayNames, array_pop( $dayNames ) );
    array_unshift( $dayNamesShort, array_pop( $dayNamesShort ) );

    $timestamp = strtotime( '15-january-2000' );

    for( $i = 0; $i < 12; $i++ ):
      $monthNames[] = date_i18n( 'F', $timestamp );
      $monthNamesShort[] = date_i18n( 'M', $timestamp );
      $timestamp = strtotime('+1 month', $timestamp );
    endfor;

    wp_localize_script( 'fvw-framework-script', 'fvwFramework', array(
      'ajaxURL' => admin_url( 'admin-ajax.php' ),
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'mobilemenuBack' => __( 'Zurück', 'fvw-framework' ),
      'locale' => get_locale(),
      'localeShort' => substr( get_locale(), 0, 2 ),
      'dayNames' => $dayNames,
      'dayNamesShort' => $dayNamesShort,
      'monthNames' => $monthNames,
      'monthNamesShort' => $monthNamesShort,
      'dateFormat' => get_option( 'date_format' ),
      'timeFormat' => get_option( 'time_format' ),
      'datetimeFormat' => get_option( 'date_format' ) . ', ' . get_option( 'time_format' ),
    ) );
  } );

  // Backend scripts and styles
  add_action( 'admin_enqueue_scripts', function() {
    wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/2b14856f6a.js', array() );
  });
