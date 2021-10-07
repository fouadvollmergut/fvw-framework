<?php





	class FVW_FORM {



		/********************** PROPERTIES **********************/


		public $fields = array();

		public $classes = array(

			'label' => 'formLabel',
			'required' => 'formRequired',
			'error' => 'formError',
			'part' => 'formPart',
			'section' => 'formSection',
			'note' => 'formNote',

			'button' => 'formButton',
			'text' => 'formText',
			'textarea' => 'formArea',
			'select' => 'formSelect',
			'hidden' => 'formHidden',
			'checkbox' => 'formCheckbox',

		);

		public $errors = array();

		public $status = 'ready';

		public $data = null; // Used to hold custom form data








		/********************** CONSTRUCTOR **********************/


		# Constructor

		public function __construct( $key, $name, $fields, $settings = null ) {



			// Create data holder

			$this->data = new stdClass();

			

			// Default settings

			$defaults = array(

				'key' => $key,
				'name' => $name,
				'method' => 'post',
				'action' => get_permalink( get_queried_object_id() ),

				'autoprivacy' => true, // Automatic privacy checkbox
				'autohandler' => true, // Automatic status handling (success and error messages)

				'autohandler_success_title' => __( 'Vielen Dank für Ihre Anfrage', 'fvw-framework' ),
				'autohandler_success_text' => __( 'Es wird sich schnellstmöglich einer unserer Mitarbeiter bei Ihnen melden.', 'fvw-framework' ),
				'autohandler_error_title' => __( 'Fehler', 'fvw-framework' ),
				'autohandler_error_text' => __( 'Es gab ein technisches Problem bei der Bearbeitung Ihrer Anfrage.', 'fvw-framework' ),

			);


			// Merge defaults with settings

			$settings = wp_parse_args( $settings, $defaults );


			// Assign settings to properties

			foreach( $settings AS $key => $value ) $this->$key = $value;







			// Register fields

			if( $fields ) foreach( $fields AS $key => $settings ) $this->register( $key, $settings );


			// Register privacy checkbox if enabled

			if( $this->autoprivacy ) $this->register( 'privacy', array(

				'type' => 'checkbox',
				// 'name' => __( 'Datenschutz', 'fvw-framework' ),
				'label' => apply_filters( 'fvw_privacy_checkbox', sprintf( __( 'Ich habe die %sDatenschutzerklärung%s zur Kenntnis genommen. Ich stimme zu, dass meine Angaben zur Kontaktaufnahme und für Rückfragen dauerhaft gespeichert werden. Diese Einwilligung kann jederzeit mit Wirkung für die Zukunft widerrufen werden.', 'fvw-framework' ), '<a tabindex="-1" href="' . get_privacy_policy_url() . '" target="_blank">', '</a>' ) ),

			) );





			// Fetch values

			foreach( $this->fields AS $field ):

				if( $this->method() == 'post' ):

					$field->value( isset( $_POST[ $field->name() ] ) ? $_POST[ $field->name() ] : $field->default() );

				else:

					$field->value( isset( $_GET[ $field->name() ] ) ? $_GET[ $field->name() ] : $field->default() );

				endif;

			endforeach;







			// On form submit/send

			if( $this->send() ):




				// Run validations
							
				foreach( $this->fields AS $field ):


					if( isset( $field->validate ) AND $field->validate instanceof Closure ):



						// Custom validation callback

						$callable = $field->validate;
						$callable( $this, $field );



					else:


						// Automatic validations

						if( $field->required AND $field->value() == '' ) $field->error( __( 'Dieses Feld ist erforderlich', 'fvw-framework' ) );

						if( $field->type() == 'email' AND !filter_var( $field->value(), FILTER_VALIDATE_EMAIL ) ) $field->error( __( 'Feld ist keine gültige E-Mail-Adresse', 'fvw-framework' ) );

						if( $field->type() == 'number' AND !is_numeric( $field->value() ) ) $field->error( __( 'Feld ist keine gültige Nummer', 'fvw-framework' ) );

						if( $field->type() == 'number' AND $field->min !== false AND $field->value() < $field->min ) $field->error( sprintf( __( 'Wert darf nicht kleiner als %s sein', 'fvw-framework' ), $field->min ) );

						if( $field->type() == 'number' AND $field->max !== false AND $field->value() > $field->max ) $field->error( sprintf( __( 'Wert darf nicht größer als %s sein', 'fvw-framework' ), $field->max ) );

						if( $field->type() == 'datetime-local' AND !preg_match('/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/', $field->value() ) ) $field->error( __( 'Feld muss im Format "yyyy-mm-ddThh:mm" sein', 'fvw-framework' ) );

						if( $field->type() == 'date' AND !preg_match('/(\d{4})-(\d{2})-(\d{2})/', $field->value() ) ) $field->error( __( 'Feld muss im Format "yyyy-mm-dd" sein', 'fvw-framework' ) );

						if( $field->type() == 'time' AND !preg_match('/(\d{2}):(\d{2})/', $field->value() ) ) $field->error( __( 'Feld muss im Format "hh:mm" sein', 'fvw-framework' ) );



						// Predefined validation shortcuts

						if( isset( $field->validate ) ) foreach( explode( '|', $field->validate ) AS $validation ):


							$validation = explode( ':', $validation );
							$rule = isset( $validation[0] ) ? $validation[0] : null;
							$check = isset( $validation[1] ) ? $validation[1] : null;


							switch( $rule ):

								case 'email':

									if( $field->value() AND !filter_var( $field->value(), FILTER_VALIDATE_EMAIL ) ) $field->error( __( 'Feld ist keine gültige E-Mail-Adresse', 'fvw-framework' ) );

								break;

								case 'numeric':

									if( $field->value() AND !is_numeric( $field->value() ) ) $field->error( __( 'Feld ist keine gültige Nummer', 'fvw-framework' ) );

								break;

								case 'min':

									if( $field->value() AND strlen( $field->value() ) < $check ) $field->error( sprintf( __( 'Text darf nicht kürzer als %s Zeichen sein', 'fvw-framework' ), $check ) );

								break;

								case 'max':

									if( $field->value() AND strlen( $field->value() ) > $check ) $field->error( sprintf( __( 'Text darf nicht länger als %s Zeichen sein', 'fvw-framework' ), $check ) );

								break;

							endswitch;


						endforeach;




					endif;


				endforeach;






				// Run sanitizers (only if validations are ok)
							
				if( !$this->error() ) foreach( $this->fields AS $field ):


					if( isset( $field->sanitize ) AND $field->sanitize instanceof Closure ):



						// Custom sanitation callback

						$callable = $field->sanitize;
						$callable( $this, $field );




					else:




						// Automatic sanitizers

						if( $field->type() == 'datetime' AND !empty( $field->value() ) ) $field->value( date_i18n( get_option( 'date_format' ) . ', '. get_option( 'time_format' ), strtotime( $field->value() ) ) );
						
						if( $field->type() == 'date' AND !empty( $field->value() ) ) $field->value( date_i18n( get_option( 'date_format' ), strtotime( $field->value() ) ) );

						if( $field->type() == 'time' AND !empty( $field->value() ) ) $field->value( date_i18n( get_option( 'time_format' ), strtotime( $field->value() ) ) );



						// Predefined sanitizers

						if( isset( $field->sanitize ) ) foreach( explode( '|', $field->sanitize ) AS $sanitizer ):


							$sanitizer = explode( ':', $sanitizer );
							$rule = $sanitizer[0];
							$check = $sanitizer[1];


							switch( $rule ):

								case 'void': // For empty values

									if( $field->value() === '' ) $field->value( $check ?: '–' );

								break;

								case 'title': // For title select

									if( $field->value() === '1' ):

										$field->value( 'Herr' );

									elseif( $field->value() === '0' ):

										$field->value( 'Frau' );

									else:

										$field->value( '' );

									endif;

								break;

								case 'trim':

									$field->value( trim( $field->value() ) );

								break;

								case 'ucfirst':

									$field->value( ucfirst( $field->value() ) );

								break;

								case 'strtolower':

									$field->value( strtolower( $field->value() ) );

								break;

								case 'strtoupper':

									$field->value( strtoupper( $field->value() ) );

								break;

							endswitch;


						endforeach;


					endif;




				endforeach;



				// Set status

				$this->status = $this->error() ? 'error' : 'done';



			endif;


		}






		/********************** HELPER **********************/


		# Google recaptcha enabled

		public function recaptcha() {

			return ( fvw()->setting( 'integration/google_recaptcha/key' ) AND fvw()->setting( 'integration/google_recaptcha/secret' ) ) ? true : false;

		}


		# Return form key (sanitized)

		public function key() {

			return 'form_' . sanitize_key( $this->key );

		}


		# Returns form name

		public function name() {

			return $this->name;

		}



		# Returns current form method

		public function method() {

			return strtolower( $this->method ) == 'get' ? 'get' : 'post';

		}



		# Check if form was submitted (if nonce or recaptcha failed the request is ignored)

		public function send() {



			// Google recaptcha check

			if( $this->recaptcha() ):

				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify' );
				curl_setopt( $ch, CURLOPT_POST, 1 );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( array( 'secret' => fvw()->setting( 'integration/google_recaptcha/secret' ), 'response' => $_POST[ $this->key() . '_token' ] ) ) );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				$chresult = curl_exec( $ch );
				curl_close( $ch );
				$response = json_decode( $chresult, true );
				 
				if( $response[ 'success' ] != '1' OR $response[ 'action' ] != $this->name() OR $response[ 'score' ] <= 0.5 ) return false;

			endif;



			// Honeypot field check

			if( !isset( $_POST[ $this->key() . '_email' ] ) OR $_POST[ $this->key() . '_email' ] != '' ):

				return false;

			endif;



			// WordPress nonce check

			if( !isset( $_POST[ $this->key() . '_nonce' ] ) OR !wp_verify_nonce( $_POST[ $this->key() . '_nonce' ], 'form_submit' ) ):

				return false;

			endif;




			// If all passed its send

			return true;




			// Deprecated since v23

			// if( $this->method() == 'post' AND isset( $_POST[ $this->key() . '_nonce' ] ) AND wp_verify_nonce( $_POST[ $this->key() . '_nonce' ], 'form_submit' ) ):

			// 	return true;

			// elseif( $this->method() == 'get' AND isset( $_GET[ $this->key() . '_nonce' ] ) AND wp_verify_nonce( $_GET[ $this->key() . '_nonce' ], 'form_submit' ) ):

			// 	return true;

			// else:

			// 	return false;

			// endif;


		}



		# Get and set custom data

		public function data( $key, $set = false ) {

			if( $set ) $this->data->$key = $set;

			return isset( $this->data->$key ) ? $this->data->$key : null;

		}



		# Returns all form values based on registered fields

		public function values() {

			$values = array();

			foreach( $this->fields AS $field ):

				$values[ $field->key() ] = $field->value();

			endforeach;

			return $values;

		}



		# Returns a single field value

		public function value( $field, $set = null ) {

			if( $fieldObject = $this->field( $field ) ):

				return $fieldObject->value( $set );

			else:

				return null;

				fvw()->error( 'There is no field "' . $field . '"' );

			endif;

		}



		# Replaces variable placeholders in text

		public function map( $text, $values = null ) {


			// Rewrite email for honeypot spam protection

			$text = str_replace( '[email]', '[honeypot]', $text );


			// Get values

			$defaults = $this->values();


			// Add custom values

			$values = wp_parse_args( $values, $defaults );


			// Replace and return

			return fvw()->tools()->mapString( $text, $values );


		}



		# Setting and getting errors

		public function error( $field = null, $set = null ) {

			if( $field AND $set ):

				if( !is_array( $field ) ) $field = explode( ',', $field );

				foreach( $field as $key ) $this->errors[ trim( $key ) ] = $set;

			elseif( $field ):

				return isset( $this->errors[ $field ] ) ? $this->errors[ $field ] : null;

			else:

				return $this->errors;

			endif;
			

		}







		/********************** STATUS **********************/


		# Get or set form status

		# ready (form waiting for input)
		# error (validation failed)
		# done (validation and sanitation done)

		# failure (custom action failed) (not set automatically)
		# success (custom action succeded) (not set automatically)

		public function status( $set = null ) {

			if( $set !== null ) $this->status = $set;

			return isset( $_GET[ $this->key() . '_status' ] ) ? $_GET[ $this->key() . '_status' ] : $this->status;

		}




		# Redirect to success page (for preventing resubmit of form data)

		public function redirect_success() {


			$url = add_query_arg( $this->key() . '_status', 'success', $this->action ) . '#' . $this->key();

			if( headers_sent() ):

				echo( "<script>location.href='$url'</script>" );

			else:

				wp_safe_redirect( $url );

			endif;

			exit;


		}




		# Redirect to failure page (for preventing resubmit of form data)

		public function redirect_failure() {


			$url = add_query_arg( $this->key() . '_status', 'failure', $this->action ) . '#' . $this->key();

			if( headers_sent() ):

				echo( "<script>location.href='$url'</script>" );

			else:

				wp_safe_redirect( $url );

			endif;

			exit;


		}















		/********************** TEMPLATING **********************/


		# Returns a class

		public function class( $class ) {

			return $this->classes[ $class ];

		}



		# Opening form tag

		public function open() {


			if( $this->autohandler AND ( $this->status() == 'failure' OR $this->status() == 'success' ) ):


				echo '<div class="modulePadding" id="' . $this->key() . '">';
					
					if( $this->status() == 'success' ):

						echo '<h1>' . $this->autohandler_success_title . '</h1>';

						echo '<div>' . $this->autohandler_success_text . '</div>';

						fvw()->track( 'Formular', $this->name(), 'success' );

					else:

						echo '<h1>' . $this->autohandler_error_title . '</h1>';

						echo '<div>' . $this->autohandler_error_text . '</div>';

						fvw()->track( 'Formular', $this->name(), 'failure' );

					endif;

				echo '</div>';


				ob_start();


			endif;



			// Attributes

			$attributes = array(
				'id' => $this->key(),
				'class' => 'fvwForm',
				'action' => $this->action . '#' . $this->key(),
				'method' => $this->method(),
			);

			if( $this->has_field_type( 'file' ) ) $attributes[ 'enctype' ] = 'multipart/form-data';



			// Render

			echo '<form ' . fvw()->tools()->attributes( $attributes ) . '>';


		}



		# Closing form tag

		public function close() {



			echo '</form>';



			// Recaptcha integration

			if( $this->recaptcha() ):

				echo "\n\n\n\n\t\t\t\t<script>
					jQuery( '#" . $this->key() . "' ).submit( function( event ) {

					    event.preventDefault();

					    grecaptcha.ready( function() {
					        grecaptcha.execute( '" . fvw()->setting( 'integration/google_recaptcha/key' ) . "', { action: '" . $this->name() . "' } ).then( function( token ) {
					            jQuery( '#" . $this->key() . "' ).prepend( '<input type=\"hidden\" name=\"" . $this->key() . "_token\" value=\"' + token + '\">' );
					            jQuery( '#" . $this->key() . "' ).unbind( 'submit' ).submit();
					        } );;
					    } );

					} );
				</script>";

  			endif;


			

			if( $this->autohandler ):

				if( $this->status() == 'failure' OR $this->status() == 'success' ):

					ob_end_clean();

				else:

					echo ob_get_clean();

				endif;

			endif; 


		}



		# Submit button

		public function submit( $text = null, $buttonClass = null, $privacyClass = 'mt' ) {



			// WordPress nonce field

			wp_nonce_field( 'form_submit', $this->key() . '_nonce' );



			// Form honeypot field

			echo '<div class="fvw_form_hpwrapper">';
			echo '<label class="formLabel" for="' . $this->key() . '_email">E-Mail</label>';
			echo '<input class="formText" id="' . $this->key() . '_email" type="email" name="' . $this->key() . '_email" />';
			echo '</div>';


			// Auto privacy checkbox

			if( $this->autoprivacy ) $this->render( 'privacy', $privacyClass );




			// Form submit buton

			$class_handler = array();
			$class_handler[] = $this->class( 'button' );
			if( $buttonClass ) $class_handler[] = $buttonClass;


			echo '<button type="submit" class="' . trim( implode( ' ', $class_handler ) ) . '">' . ( $text ?: __( 'Senden', 'fvw-framework' ) ) . '</button>';


		}



		# Required sign

		public function required() {

			echo '<span class="' . $this->class( 'required' ) . '" title="' . __( 'Dieses Feld ist erforderlich', 'fvw-framework' ) . '">*</span>';

		}



		# Section text

		public function section( $text ) {

			echo '<strong class="' . $this->class( 'section' ) . '">' . $text . '</strong>';

		}





















		/********************** FIELDS **********************/


		# Create field object

		public function register( $key, $settings = null ) {

			$this->fields[ $key ] = new FVW_FIELD( $key, $this, $settings );

		}


		# Return field object

		public function field( $key ) {

			return isset( $this->fields[ $key ] ) ? $this->fields[ $key ] : null;

		}


		# Check if specific field type exists in form

		public function has_field_type( $type ) {

			foreach( $this->fields as $key => $field )
				if( $field->type() == $type ) return true;

			return false;

		}


		# Render a field object

		public function render( $field, $class = null ) {

			if( $fieldObject = $this->field( $field ) ):

				$fieldObject->render( $class );

			else:

				fvw()->error( 'There is no field "' . $field . '"' );

			endif;
			

		}


















	}


	



