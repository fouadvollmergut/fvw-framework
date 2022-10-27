import './assets/styles/styles.scss';

import $ from 'jquery';
import flatpickr from 'flatpickr';
import { German } from 'flatpickr/dist/l10n/de.js';
import { French } from 'flatpickr/dist/l10n/fr.js';
import { Croatian } from 'flatpickr/dist/l10n/hr.js';

const jQuery = $;

window.$ = jQuery;

/************************************ THE FVW OBJECT ************************************/

const fvw = new Object();

window.fvw = fvw;


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

fvw.hash.get = function () {
  return window.location.hash.substring( 1, window.location.hash.length );
}


// Sets hash

fvw.hash.set = function (set) {
  window.location.hash = set;
}


// Trigger update event

fvw.hash.update = function () {
  window.dispatchEvent( new HashChangeEvent( 'hashchange' ) )
}


// Remove hash

fvw.hash.remove = function () {
  history.replaceState( null, null, ' ' );
  fvw.hash.update();
}


// Returns remains if needles matches or false

fvw.hash.beginsWidth = function (needle) {
  return fvw.hash.get().substring( 0, needle.length ) == needle ? fvw.hash.get().substring( needle.length, fvw.hash.get().length ) : false;
}



/************************************ MODIFY URL PARAMETERS ************************************/


// Register object

fvw.url = new Object();


// Get complete url

fvw.url.get = function () {
  return window.location.href;
}


// Set complete url

fvw.url.set = function (url) {
  window.history.replaceState( {}, "", url );
}


// Load url

fvw.url.load = function (url) {
  window.location.href = url;
}


// Get url without parameters

fvw.url.base = function () {
  return [location.protocol, '//', location.host, location.pathname].join('');
}


// Remove all parameters from url 

fvw.url.reset = function () {
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

jQuery( document ).ready( function( jQuery ) {

  console.log( 'Website by https://fouadvollmer.de/' );
  
  /****************************** FLATPICKR (DATE, TIME AND DATETIME INPUTS) ******************************/

  // General settings

  jQuery( '.fvw_flatpickr' ).each( function() {


    // Variables

    var current = jQuery( this );
    var type = current.attr( 'data-type' );
    var minDate = current.attr( 'data-datemin' );
    var maxDate = current.attr( 'data-datemax' );


    const locale = {
      "de": German,
      "fr": French,
      "hr": Croatian
    }[fvwFramework.localeShort]

    // General settings

    var settings = {

      disableMobile: true,
      locale: locale,

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

    flatpickr( current.find('input')[0], settings );

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

    console.log(set);

    var d = new Date;
    d.setTime( d.getTime() + 24*60*60*1000*365 );
    document.cookie = "fvw_privacy=" + set + ";path=/;expires=" + d.toGMTString();

    console.log(document.cookie);

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

	
	/*!
	 * A simple jQuery plugin for creating animated drilldown menus.
	 *
	 * @name jQuery Drilldown
	 * @version 0.1.2
	 * @requires jQuery v1.7+
	 * @author Aleksandras Nelkinas
	 * @license [MIT]{@link http://opensource.org/licenses/mit-license.php}
	 *
	 * Copyright (c) 2013 Aleksandras Nelkinas
	 */

	;(function (factory) {
	  if (typeof define === 'function' && define.amd) {
	    // AMD support
	    define(['jquery'], factory);
	  } else {
	    factory(jQuery);
	  }
	}(function ($, undefined) {

	  'use strict';

	  var pluginName = 'drilldown',
	      defaults;

	  defaults = {
	    event: 'click',
	    selector: 'a',
	    speed: 100,
	    cssClass: {
	      container: pluginName + '-container',
	      root: pluginName + '-root',
	      sub: pluginName + '-sub',
	      back: pluginName + '-back'
	    }
	  };

	  function Plugin(element, options) {
	    this._name = pluginName;
	    this._defaults = defaults;

	    this.element = element;
	    this.$element = $(element);

	    this.options = $.extend({}, defaults, options);

	    this.init();
	  }

	  Plugin.prototype = {

	    history: [],
	    css: {
	      'float': 'left',
	      'width': null
	    },

	    /**
	     * Initializes plugin.
	     */
	    init: function () {
	      var self = this;

	      this.$container = this.$element.find('.' + this.options.cssClass.container);
	      this.$element.on(this.options.event, this.options.selector, function (e) {
	        self.handleAction.call(self, e, $(this));
	      });
	    },

	    /**
	     * Handles user action and decides whether or not and where to drill.
	     *
	     * @param {jQuery.Event} e
	     * @param {jQuery}       $trigger
	     */
	    handleAction: function (e, $trigger) {
	      var $next = $trigger.nextAll('.' + this.options.cssClass.sub),
	          preventDefault = true;

	      if ($next.length) {
	        this.down($next);
	      } else if ($trigger.closest('.' + this.options.cssClass.back).length) {
	        this.up();
	      } else {
	        preventDefault = false;
	      }

	      if (preventDefault && $trigger.prop('tagName') === 'A') {
	        e.preventDefault();
	      }
	    },

	    /**
	     * Drills down (deeper).
	     *
	     * @param {jQuery} $next
	     */
	    down: function ($next) {
	      var self = this,
	          $current;

	      if (!$next.length) {
	        return;
	      }

	      this.css.width = this.$element.outerWidth();
	      this.$container.width(this.css.width * 2);

	      $current = this.$container.find('.' + this.options.cssClass.root).first();

	      $next = $current.clone().html($next.html());
	      this.$container.append($next);

	      this.animateDrilling(-1 * this.css.width, function () {
	        var $current = $next.prev();

	        self.history.push($current);

	        self.restoreState.call(self, $current, $next);
	      });
	    },

	    /**
	     * Drills up (back).
	     */
	    up: function () {
	      var self = this,
	          $next = this.history.pop();

	      this.css.width = this.$element.outerWidth();
	      this.$container.width(this.css.width * 2);

	      this.$container.prepend($next);

	      this.animateDrilling(0, function () {
	        var $current = $next.next();

	        self.restoreState.call(self, $current, $next);
	      });
	    },

	    /**
	     * Animates drilling process.
	     *
	     * @param {Number}   marginLeft Target margin-left.
	     * @param {Function} callback
	     */
	    animateDrilling: function (marginLeft, callback) {
	      var $roots = this.$container.children('.' + this.options.cssClass.root);

	      $roots.css(this.css);

	      // if( marginLeft === 0 ) {

	      // 	this.$container.animate( { 'height': $roots.eq(0).height() }, this.options.speed );

	      // } else {

	      // 	this.$container.animate( { 'height': $roots.eq(1).height() }, this.options.speed );

	      // }

	      

	      $roots.first().animate({
	        'margin-left': marginLeft
	      }, this.options.speed, callback);
	    },

	    /**
	     * Restores initial menu's state.
	     *
	     * @param {jQuery} $current
	     * @param {jQuery} $next
	     */
	    restoreState: function ($current, $next) {
	      $next.css({
	        'float': 'none',
	        'width': 'auto',
	      });

	      $current.remove();

	      this.$container.width('auto');
	    }

	  };

	  $.fn[pluginName] = function (options) {
	    return this.each(function () {
	      if (!$.data(this, pluginName)) {
	        $.data(this, pluginName, new Plugin(this, options));
	      }
	    });
	  };

	}));







	/*!
		
		Creates a mobile menu based on drilldown.js from Aleksandras Nelkinas

		@name Mobile Menu
		@author Johannes Grandy
		@license Do whatever you want

		Copyright (c) 2014 Johannes Grandy

	*/


	(function ( $ ) {


		// Methods and init

		var methods = {

	        init : function( options ) {
	        	

	        	// Set settings

	        	jQuery.fn.mobilemenu.settings = $.extend( {}, jQuery.fn.mobilemenu.settings, options );
	        	


	        	// Get settings

	        	var settings = jQuery.fn.mobilemenu.settings;



				// Clone the menu

				var clone = this.clone();



				// Prepare the clone with aditional classes

				clone.addClass( 'mobilemenu_drilldownmenu' ).find( 'li' ).each( function() {

					var drli = jQuery( this );
					var drul = drli.children( 'ul' );
					var drxt = drli.children( 'a' ).text();
					var drxa = drli.children( 'a' ).attr( 'href' );

					drul.addClass( 'mobilemenu_drilldownsubmenu' ).hide();

					if( drul.length > 0 ) {

						drli.addClass( 'mobilemenu_drilldowndeeper' );

						if( settings.cloneParents ) {

							drul.prepend( '<li class="mobilemenu_drilldownparent"><a href="' + drxa + '">' + drxt + '</a></li>' );

						}

						jQuery( '<li class="mobilemenu_drilldownback" style="position: relative;"><a href="#">' + settings.drilldownBack + '</a></li>' ).prependTo( drul );
					
					}
						
				} );
			

			
				// Place DOM elements

		        var overlay = jQuery( '<div id="mobilemenu_overlay"></div>').appendTo( 'body' );
				
				var menu = jQuery('<div id="mobilemenu_slide"></div>').appendTo( 'body' );
				
				var drilldown = jQuery('<div id="mobilemenu_main"></div>').appendTo( menu );
				
				var drilldownInner = jQuery('<div class="mobilemenu_drilldown"></div>').appendTo( drilldown );
				
				clone.appendTo( drilldownInner );
				
				

				// Fire Aleksandras drilldown script

				drilldown.drilldown({

					event: 'click',
					selector: 'a',
					speed: settings.drilldownAnimationSpeed,
					cssClass: {

						container: 'mobilemenu_drilldown',
						root: 'mobilemenu_drilldownmenu',
						sub: 'mobilemenu_drilldownsubmenu',
						back: 'mobilemenu_drilldownback'

					}

				});		
			
				


				// Close slide on overlay click

				overlay.on( 'click', function( event ) {

					fvw.hash.remove();

				} );



				// Open close on hashchanges
				
				jQuery( window ).bind( 'hashchange load', function( e ) {

					if( fvw.hash.beginsWidth( 'mobilemenu' ) !== false ) {

						jQuery.fn.mobilemenu( 'open' );

					} else {

						jQuery.fn.mobilemenu( 'close' );

					}

				} );

				

				// Fire on onCreate

				settings.onCreate( settings );



	        },

	        open: function() {

	        	var settings = jQuery.fn.mobilemenu.settings;

	        	jQuery( 'body' ).addClass( 'mobilemenu_active' );

	        	// Fire on onOpen

				settings.onOpen( this, settings );

	        },

	        close: function() {

	        	var settings = jQuery.fn.mobilemenu.settings;

	        	jQuery( 'body' ).removeClass( 'mobilemenu_active' );

	        	// Fire on onClose

				settings.onClose( this, settings );

	        }

	    };




	    // The plugin caller

		jQuery.fn.mobilemenu = function( methodOrOptions ) {
		
			if ( methods[methodOrOptions] ) {

            	return methods[ methodOrOptions ].apply( this, Array.prototype.slice.call( arguments, 1 ) );

        	} else if ( typeof methodOrOptions === 'object' || ! methodOrOptions ) {

            	return methods.init.apply( this, arguments );

        	} else {

            	jQuery.error( 'Method ' +  methodOrOptions + ' does not exist on jQuery.mobilemenu' );

        	}  
			
		};




		// Default settings

		jQuery.fn.mobilemenu.settings = {

			closeOnUnload: true, // Close if a link inside the menu is clicked
			cloneParents: true, // The menu items with submenu are not clickable (because they trigger the deeper slide). Change this if this menupoints should be cloned as child of theirself

			drilldownBack: fvwFramework.mobilemenuBack, // Drilldown back text
			drilldownAnimationSpeed: 130, // Speed of the drilldown hirarchy change

			onCreate: function() {},
			onOpen: function() {},
			onClose: function() {}
						
		};





	}( jQuery ));
	
		
