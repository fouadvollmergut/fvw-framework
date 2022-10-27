<?php

	




	// Charset

	add_action( 'wp_head', 'fvw_framework_head_charset', 1 );

	function fvw_framework_head_charset() {

		echo '<meta charset="' . get_bloginfo( 'charset' ) . '"/>';
		// echo '<meta http-equiv="Content-Type" content="text/html; charset=' . get_bloginfo( 'charset' ) . '" />';

	}




	// Viewport

	add_action( 'wp_head', 'fvw_framework_head_viewport' );

	function fvw_framework_head_viewport() {

		echo '<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1, user-scalable=0" />';

	}




	// Title

	add_action( 'wp_head', 'fvw_framework_title' );

	function fvw_framework_title() {

		echo '<title>' . ( is_front_page() ? get_bloginfo( 'description' ) : wp_title( '', false ) ) . ' | ' . get_bloginfo( 'name' ) . '</title>';

	}


	

	// Analytics

	add_action( 'wp_head', 'fvw_framework_head_analytics' );

	function fvw_framework_head_analytics() {

		?>


			<?php if( $gaID = fvw()->setting( 'integration/google_analytics/id' ) AND fvw()->privacy() ): ?>

				<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gaID; ?>"></script>
				
				<script>

					document.addEventListener( "DOMContentLoaded", function() {
						var el = document.querySelectorAll( "a[href='#gaOptout" );
						for( i = 0; i < el.length; i++ ) el[i].addEventListener( 'click', gaOptout );
					} );

					function gaOptout() {
						document.cookie = 'ga-disable-<?php echo $gaID; ?>=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
						alert( '<?php _e( 'Die Erfassung Ihrer Daten wird in Zukunft verhindert.', 'fvw-framework' ); ?>');
					}

					window.dataLayer = window.dataLayer || [];
					function gtag() { dataLayer.push( arguments ); }
					gtag( 'js', new Date() );

					gtag( 'config', '<?php echo $gaID; ?>', { 'anonymize_ip': true } );

				</script>

			<?php endif; ?>



		<?php

	}

	













