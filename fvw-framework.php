<?php

  /*
   * Plugin Name: Fouad Vollmer Framework
   * Description: Technisches Framework für WordPress-Webseiten. Grundlage für verschiedene Addon-Plugins.
   * Author: Fouad Vollmer
   * Author URI: https://fouadvollmer.de/
   * Text Domain: fvw-framework
   * Domain Path: /languages/
   * Version: 29.0.1
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
  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/privacy.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/misc.php';
  require_once FVW_FRAMEWORK_BASE_PATH . 'includes/mailer.php';


  /* FACTORY FUNCTION */

  $fvw = new FVW_FACTORY;

  function fvw() {
    global $fvw;
    return $fvw;
  }


  /* STYLES AND SCRIPTS */

  // Frontend scripts and styles
  add_action( 'wp_enqueue_scripts', function() {

    // Dependency holders
    $jsDeps = array( 'jquery' );
    $cssDeps = array();

    // Jquery
    if( apply_filters( 'fvw_load_jquery', true ) ):
      wp_deregister_script( 'jquery' );
      wp_enqueue_script( 'jquery', plugins_url( '/assets/scripts/jquery.min.js', __FILE__ ), array(), '3.5.1' );
    endif;

    // Fontawesome
    if( apply_filters( 'fvw_load_fontawesome', true ) OR gdymc_logged() ):
      wp_enqueue_script( 'fontawesome', 'https://kit.fontawesome.com/2b14856f6a.js', array() );
    endif;

    // Flatpickr
    if( apply_filters( 'fvw_load_flatpickr', true ) ):
      wp_enqueue_style( 'flatpickr', FVW_FRAMEWORK_BASE_URL . 'assets/addons/flatpickr/styles/flatpickr.min.css', '4.6.3' );
      $cssDeps[] = 'flatpickr';
      
      wp_enqueue_script( 'flatpickr', FVW_FRAMEWORK_BASE_URL . 'assets/addons/flatpickr/scripts/flatpickr.min.js', array( 'jquery' ), '4.6.3' );
      wp_enqueue_script( 'flatpickr_' . substr( get_locale(), 0, 2 ), FVW_FRAMEWORK_BASE_URL . 'assets/addons/flatpickr/languages/' . substr( get_locale(), 0, 2 ) . '.min.js', array( 'jquery', 'flatpickr' ), '4.6.3' );
      $jsDeps[] = 'flatpickr';
    endif;

    // Load styles and scripts
    wp_enqueue_style( 'fvw-framework-style', plugins_url( '/assets/styles/main.css', __FILE__ ), $cssDeps );
    wp_enqueue_script( 'fvw-framework-script', plugins_url( '/assets/scripts/main.js', __FILE__ ) );

    // Google Recaptcha
    if( $recaptcha_key = fvw()->setting( 'integration/google_recaptcha/key' ) AND $recaptcha_secret = fvw()->setting( 'integration/google_recaptcha/secret' ) ):
      wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptcha_key, '4.6.3' );
    endif;

    // Mobilemenu
    if( apply_filters( 'fvw_load_mobilemenu', true ) ):
      wp_enqueue_script( 'fvw-mobilemenu', plugins_url( '/assets/scripts/mobilemenu.min.js', __FILE__ ), array( 'jquery', 'fvw-framework-script' ) );
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
  } );
