	
	

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

	    if( !localStorage.privacyNote ) jQuery( 'body' ).addClass( 'privacy' );

        jQuery( '#privacy_close' ).click( function() {

            jQuery( 'body' ).removeClass( 'privacy' );
            localStorage.privacyNote = true;

        } );








		/************************************* TABS *************************************/

        jQuery( document.body ).on( 'click', '[data-tab-scope][data-tab-trigger]', function() {

        	var scope = jQuery( this ).attr( 'data-tab-scope' );
	        var target = jQuery( this ).attr( 'data-tab-trigger' );
	        
	        fvw.tab.trigger( scope, target );
	        
		} );

		






		/************************** SMOOTH SCROLLING **************************/

		// jQuery( 'a[href*="#"]:not([href="#"])' ).click( function() {

		//     if( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') || location.hostname == this.hostname ) {

		//         var target = jQuery( this.hash );

		//         target = target.length ? target : jQuery('[name="' + this.hash.slice(1) +'"]');

		// 		if( target.length ) {

		// 		jQuery( 'html,body' ).animate( {
		// 			scrollTop: target.offset().top - 50
		// 		}, 1000 );
				
		// 		return false;

		// 		}
		//     }

		// } );

		


		

		
	}); // End function
	
	
	
