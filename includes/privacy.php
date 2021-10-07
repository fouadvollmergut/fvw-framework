<?php

	


	add_action( 'wp_footer', 'fvw_framework_privacy_overlay' );

	function fvw_framework_privacy_overlay() {

		?>
			

			<div id="privacy" class="ts">
				
				<div id="privacy_text">
					
					<?php echo apply_filters( 'fvw_privacy_notice', sprintf( __( 'Diese Webseite verwendet Cookies. Sehen Sie sich für mehr Informationen unsere %sDatenschutzrichtlinie%s an.', 'Theme' ), '<a href="' . get_privacy_policy_url() . '">', '</a>' ) ); ?>

				</div>
               
                <div id="privacy_close">

                	<span class="icon fal fa-times"></span>

                </div>

		    </div>
				

		<?php

	}











