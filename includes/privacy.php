<?php
	



	// Body class

	add_filter( 'body_class', 'fvw_framework_privacy_class' );

	function fvw_framework_privacy_class( $classes ) {

		if(	!isset( $_COOKIE[ 'fvw_privacy' ] ) AND !isset( $_GET[ 'fvw_privacy_hide' ] ) ) {

			$classes[ 'fvw_privacy' ] = 'fvw_privacy';

		}

		return $classes;
	}





	// Render Window

	add_action( 'wp_footer', 'fvw_framework_privacy_overlay' );

	function fvw_framework_privacy_overlay() {

		?>
			

			<div id="fvw_privacy_overlay"></div>


			<div id="fvw_privacy_window" class="center">


				<i id="fvw_privacy_icon" class="far fa-shield-alt"></i>

				
				<strong id="fvw_privacy_title"><?php echo apply_filters( 'fvw_privacy_title', __( 'Datenschutzhinweis', 'Theme' ) ); ?></strong>
				
				<div id="fvw_privacy_text" class="mth mb">
					
					<?php echo apply_filters( 'fvw_privacy_notice', __( 'Diese Webseite verwendet Cookies und andere Technologien zur Bereitstellung verschiedener Funktionen und/oder zur statistischen Auswertung.', 'Theme' ) ); ?>

				</div>
				
				
				<a href="#" class="formButton" data-fvw-privacy="enabled">Zulassen</a>
				
				<div class="mth tb ts">
					<a href="#" data-fvw-privacy="disabled">Ablehnen</a> · <a href="<?php echo add_query_arg( 'fvw_privacy_hide', '', get_privacy_policy_url() ); ?>">Datenschutzerklärung</a>
				</div>

		    </div>


		<?php

	}











