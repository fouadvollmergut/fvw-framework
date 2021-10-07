<?php





	class FVW_TOOLS {





		# Converts array into html attribute string

		function attributes( $attributeArray ) {


			$attributeHandler = array();

			foreach( $attributeArray as $key => $value ):

				if( $value === null ):

					$attributeHandler[] = $key;

				else:

					$attributeHandler[] = $key . '="' . esc_attr( $value ) . '"';

				endif;

			endforeach;

			$attributeString = implode( ' ', $attributeHandler );


			return $attributeString;


		}




		# Sets a cookie

		function cookie_set( $key, $value ) {

			setcookie( $key, $value, 0, COOKIEPATH, COOKIE_DOMAIN );
			$_COOKIE[ $key ] = $value;

		}


		# Removes a cookie

		function cookie_remove( $key ) {

			setcookie( $key, null, -1, COOKIEPATH, COOKIE_DOMAIN );
			unset( $_COOKIE[ $key ] );

		}



		# Returns current URL

		function current_url() {

			return ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];

		}






		# Creates a random string

		function randomString( $length = 5, $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789', $prepend = '', $append = '' ) {


			// Create random string

		    for( $i = 0; $i < $length; $i++ ) $prepend .= $alphabet[ rand( 0, strlen( $alphabet ) - 1 ) ];


		    // Return code

			return $prepend . $append;

		}



		# Converts a decimal to a base representation

		function baseEncode( $decimal, $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789' ) {


			$length = strlen( $alphabet );
			$base = '';


			while( $decimal ) :

		        $decimal = ( $decimal - ( $r = $decimal % $length ) ) / $length;     
		        $base = $alphabet{$r} . $base;
		    
		    endwhile;


			return $base;

		}


		# Converts a base representation to a decimal

		function baseDecode( $base, $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789' ) {

			$length = strlen( $alphabet );
		    $decimal = 0;

		    foreach( str_split( $base ) as $letter ) {

		        $decimal = ( $decimal*$length ) + strpos( $alphabet, $letter );

		    }

		    return $decimal;

		}



		# Replaces placeholders with values

		function mapString( $text, $values ) {


			// Create replacement arrays

			$k = array_map( function( $v ) {

				return "[$v]";

			}, array_keys( $values ) );

			$v = array_values( $values );



			// Replace and return

			return str_replace( $k, $v, $text );

		}



	}


	



