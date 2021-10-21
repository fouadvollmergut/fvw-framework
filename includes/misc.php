<?php

	

	


    /**************************** WORDPRESS ADJUSTMENTS ****************************/

    // Remove big image threshold

	add_filter( 'big_image_size_threshold', '__return_false', 10 );


	// Remove gutenberg resources

	add_action( 'wp_enqueue_scripts', function() {

		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );

	}, 100 );


	// Remove generator tag

	remove_action( 'wp_head', 'wp_generator' );
	








	/**************************** GDYMC MODULES FOLDER ****************************/


	add_filter( 'gdymc_modules_folder', 'change_gdymc_module_folder' );

    function change_gdymc_module_folder( $content ) {

        return get_template_directory() . '/modules';

    }






	/**************************** SUPPORT SVG UPLOADS ****************************/	


	add_filter( 'upload_mimes', 'svg_support' );

	function svg_support( $mimes ) {

	  $mimes['svg'] = 'image/svg+xml';
	  
	  return $mimes;

	}





	/**************************** DEFAULT POST SORTING BY MENU ORDER ****************************/	


	add_post_type_support( 'page', 'page-attributes' );
	add_post_type_support( 'post', 'page-attributes' );

	add_action( 'pre_get_posts', 'fvw_framework_post_sorting', 10 );

	function fvw_framework_post_sorting( $query ) {
		

		if( !is_admin() AND $query->is_main_query() AND get_query_var( 'post_type' ) != 'faq' ):

			$query->set( 'orderby', array( 

				'menu_order' => 'DESC',
				'date' => 'ASC',

			) ); 


		endif;

		return $query;


	}





	/**************************** 404 TEMPLATE ****************************/


	add_action( 'template_include', 'fvw_404_template' );

	function fvw_404_template( $template ) {


		if( is_404() ):

			if( $locate = locate_template( '404.php' ) ):

				return $locate;

			else:

				return FVW_FRAMEWORK_BASE_PATH . 'includes/404.php';

			endif;

		else:

			return $template;

		endif;
		

	}






	/**************************** BACKEND ADJUSTMENTS ****************************/


	// Remove admin menu pages and add menu page

	add_action( 'admin_menu', 'fvw_admin_menu_adjustments' );

	function fvw_admin_menu_adjustments() {


		// Adjust editor role

		$role_object = get_role( 'editor' );
		if( !$role_object->has_cap( 'edit_theme_options' ) ) $role_object->add_cap( 'edit_theme_options' );


		// Adjust menus

		if( current_user_can( 'editor' ) ):

			remove_menu_page( 'index.php' ); // Dashboard entfernen
			remove_menu_page( 'profile.php' ); // Profil entfernen
			remove_menu_page( 'tools.php' ); // Werkzeuge entfernen
			remove_menu_page( 'themes.php' ); // Design entfernen

			if( get_theme_support( 'menus' ) ) add_menu_page( __( 'Menus' ), __( 'Menus' ), 'editor', 'nav-menus.php', '', 'dashicons-align-left', 60 );
			//if( get_theme_support( 'widgets' ) ) add_menu_page( __( 'Widgets' ), __( 'Widgets' ), 'editor', 'widgets.php', '', 'dashicons-screenoptions', 65 );

		endif;
		

	}


	// Set pages as default admin page

	add_action( 'admin_init', 'fvw_admin_default_page' );  

	function fvw_admin_default_page() {

		global $pagenow;

		// Redirect dashboard

		if( current_user_can( 'editor' ) AND in_array( $pagenow, array( 'index.php' ) ) ):

			wp_redirect( admin_url( '/edit.php?post_type=page' ), 301 ); exit;

		endif;


		// Redirect forbidden pages

		if( current_user_can( 'editor' ) AND in_array( $pagenow, array( 'themes.php', 'widgets.php', 'theme-editor.php', 'customize.php' ) ) ):

			wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );

		endif;

	}


	// Allow editors to edit the privacy page

	add_action( 'map_meta_cap', 'fvw_admin_soften_privacy_permissions', 1, 4 );

	function fvw_admin_soften_privacy_permissions( $caps, $cap, $user_id, $args ) {


		if( 'manage_privacy_options' === $cap ):

			$manage_name = is_multisite() ? 'manage_network' : 'manage_options';
			$caps = array_diff( $caps, [ $manage_name ] );

		endif;

		return $caps;

	}



	// Remove admin bar (probably?)

    remove_action( 'admin_init', 'wp_admin_bar_init' );

	    














