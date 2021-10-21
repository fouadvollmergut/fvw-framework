	


	/************************************ THE FVW OBJECT ************************************/

	var fvw = new Object();







	/************************************ CREATE GA TRACKING SNIPPET ************************************/

	fvw.track = function( category, action, label ) {

		gtag( 'event', action, {
			'event_category': category,
			'event_label': label,
		} );

	};




	/************************************ CONTROLLING TABS ************************************/


	// Register object

	fvw.tab = new Object();


	// Trigger tab

	fvw.tab.trigger = function( scope, target ) {

		jQuery( '[data-tab-scope="' + scope + '"][data-tab-trigger="' + target + '"]' ).addClass( 'active' );
        jQuery( '[data-tab-scope="' + scope + '"][data-tab-target="' + target + '"]' ).addClass( 'active' );

        jQuery( '[data-tab-scope="' + scope + '"]:not([data-tab-target="' + target + '"]):not([data-tab-trigger="' + target + '"])' ).removeClass( 'active' );

	}





	/************************************ MODIFY URL HASH ************************************/


	// Register object

	fvw.hash = new Object();




	// Get hash without hashtag

	fvw.hash.get = function() {

		return window.location.hash.substring( 1, window.location.hash.length );

	}


	// Sets hash

	fvw.hash.set = function( set ) {

		window.location.hash = set;

	}


	// Trigger update event

	fvw.hash.update = function( set ) {

		//jQuery( window ).trigger( 'hashchange' );
		window.dispatchEvent( new HashChangeEvent( 'hashchange' ) )

	}


	// Remove hash

	fvw.hash.remove = function() {

		history.replaceState( null, null, ' ' );
		
		fvw.hash.update();


	}


	// Returns remains if needles matches or false

	fvw.hash.beginsWidth = function( needle ) {

		return fvw.hash.get().substring( 0, needle.length ) == needle ? fvw.hash.get().substring( needle.length, fvw.hash.get().length ) : false;

	}









	/************************************ MODIFY URL PARAMETERS ************************************/


	// Register object

	fvw.url = new Object();





	// Get complete url

	fvw.url.get = function() {

		return window.location.href;

	}


	// Set complete url

	fvw.url.set = function( url ) {

		window.history.replaceState( {}, "", url );

	}


	// Load url

	fvw.url.load = function( url ) {

		window.location.href = url;

	}


	// Get url without parameters

	fvw.url.base = function() {

		return [location.protocol, '//', location.host, location.pathname].join('');

	}


	// Remove all parameters from url 

	fvw.url.reset = function() {

	    window.history.replaceState({}, "", fvw.url.base());

	}


	// Add/update/remove paramter from url (no working for parameter with single letter)

	fvw.url.update = function( key, value ) {

	    var urlQueryString = document.location.search,
	        newParam = key + '=' + value,
	        params = '?' + newParam;

	    // If the "search" string exists, then build params from it
	    if (urlQueryString) {

	        updateRegex = new RegExp('([\?&])' + key + '[^&]*');
	        removeRegex = new RegExp('([\?&])' + key + '=[^&;]+[&;]?');

	        if( typeof value == 'undefined' || value == null || value == '' ) { // Remove param if value is empty

	            params = urlQueryString.replace(removeRegex, "$1");
	            params = params.replace( /[&;]$/, "" );

	        } else if (urlQueryString.match(updateRegex) !== null) { // If param exists already, update it

	            params = urlQueryString.replace(updateRegex, "$1" + newParam);

	        } else { // Otherwise, add it to end of query string

	            params = urlQueryString + '&' + newParam;

	        }

	    }

	    window.history.replaceState({}, "", fvw.url.base() + params);

	}














		
	

	console.log( 'Website by https://fouadvollmer.de/' );




	jQuery( document ).ready( function( jQuery ) {
		




		/****************************** FLATPICKR (DATE, TIME AND DATETIME INPUTS) ******************************/


		// General settings

		jQuery( '.fvw_flatpickr' ).each( function() {


			// Variables

			var current = jQuery( this );
			var type = current.attr( 'data-type' );
			var minDate = current.attr( 'data-datemin' );
			var maxDate = current.attr( 'data-datemax' );


			// General settings

			var settings = {

				disableMobile: true,
				locale: fvwFramework.localeShort,

				dateFormat: 'Y-m-d',
				altInput: true,
				altFormat: fvwFramework.dateFormat,

				prevArrow: '<span class="icon far fa-chevron-left"></span>',
				nextArrow: '<span class="icon far fa-chevron-right"></span>',

			    minDate: minDate,
			    maxDate: maxDate,

			    // inline: true,

			}


			// Type specific settings

			if( type == 'datetime' ) {
				settings.enableTime = true;
				settings.dateFormat = 'Y-m-d\TH:i';
				settings.altFormat = fvwFramework.datetimeFormat;
				
			}

			if( type == 'daterange' ) {
				settings.mode = 'range';
			}

			if( type == 'time' ) {
				settings.altFormat = fvwFramework.timeFormat;
				settings.dateFormat = 'H:i';
				settings.enableTime = true;
    			settings.noCalendar = true;
			}


			// Disable

			if( current.attr( 'data-datedisable' ) != '' ) {

				var disable = current.attr( 'data-datedisable' ).split( ',' );
				settings.disable = [
			        function( date ) {

			        	var isoDate = new Date( date.getTime() - ( date.getTimezoneOffset() * 60000 ) ).toISOString().substring( 0, 10 );

			        	if( date.getDay() === 0 && disable.indexOf( 'sunday' ) >= 0 ) return true;
			        	if( date.getDay() === 1 && disable.indexOf( 'monday' ) >= 0 ) return true;
			        	if( date.getDay() === 2 && disable.indexOf( 'tuesday' ) >= 0 ) return true;
			        	if( date.getDay() === 3 && disable.indexOf( 'wednesday' ) >= 0 ) return true;
			        	if( date.getDay() === 4 && disable.indexOf( 'thursday' ) >= 0 ) return true;
			        	if( date.getDay() === 5 && disable.indexOf( 'friday' ) >= 0 ) return true;
			        	if( date.getDay() === 6 && disable.indexOf( 'saturday' ) >= 0 ) return true;

			        	if( disable.indexOf( isoDate ) >= 0 ) return true;

			        	return false;

			        }
			    ];

			}


			// Enable

			if( current.attr( 'data-dateenable' ) != '' ) {

				var enable = current.attr( 'data-dateenable' ).split( ',' );
				settings.enable = [
			        function( date ) {

			        	var isoDate = new Date( date.getTime() - ( date.getTimezoneOffset() * 60000 ) ).toISOString().substring( 0, 10 );

			        	if( date.getDay() === 0 && enable.indexOf( 'sunday' ) >= 0 ) return true;
			        	if( date.getDay() === 1 && enable.indexOf( 'monday' ) >= 0 ) return true;
			        	if( date.getDay() === 2 && enable.indexOf( 'tuesday' ) >= 0 ) return true;
			        	if( date.getDay() === 3 && enable.indexOf( 'wednesday' ) >= 0 ) return true;
			        	if( date.getDay() === 4 && enable.indexOf( 'thursday' ) >= 0 ) return true;
			        	if( date.getDay() === 5 && enable.indexOf( 'friday' ) >= 0 ) return true;
			        	if( date.getDay() === 6 && enable.indexOf( 'saturday' ) >= 0 ) return true;

			        	if( enable.indexOf( isoDate ) >= 0 ) return true;

			        	return false;

			        }
			    ];

			}


			// Create the picker

			current.find( 'input' ).flatpickr( settings );


		} );



		








		/****************************** MOBILEMENU (AUTOMATIC) ******************************/

		if( jQuery().mobilemenu && jQuery( '.mobilemenu_main' ).length ) {

			jQuery( '.mobilemenu_main' ).first().mobilemenu( {
			
				onCreate: function( settings ){

					console.log( 'Mobile menu automatically created' );

					jQuery( '.mobilemenu_sub' ).first().clone().appendTo( '#mobilemenu_slide' ).wrap( '<div id="mobilemenu_sub"></div>' );

				}

			} );

		}





		/****************************** FORM ACTIONS ******************************/
		
		jQuery( '.formSelect' ).mousedown( function( e ) {


		    if( jQuery( this ).find( 'option' ).length == 2 ) {

		    	var target = jQuery( this ).find( 'option:selected' );

		    	jQuery( this ).find( 'option' ).prop( 'selected', true );

		    	target.prop( 'selected', false );

		    	return false;

		    }
		    

		} );




		/************************************* PRIVACY NOTICE *************************************/

		// Set

		jQuery( '[data-fvw-privacy]' ).click( function() {

			var set = jQuery( this ).attr( 'data-fvw-privacy' );

			var d = new Date;
			d.setTime( d.getTime() + 24*60*60*1000*365 );
			document.cookie = "fvw_privacy=" + set + ";path=/;expires=" + d.toGMTString();

			location.reload();

			return false;

		} );








		/************************************* TABS *************************************/

        jQuery( document.body ).on( 'click', '[data-tab-scope][data-tab-trigger]', function() {

        	var scope = jQuery( this ).attr( 'data-tab-scope' );
	        var target = jQuery( this ).attr( 'data-tab-trigger' );
	        
	        fvw.tab.trigger( scope, target );
	        
		} );

		



		


		

		
	}); // End function
	
	
	
