
		


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




	