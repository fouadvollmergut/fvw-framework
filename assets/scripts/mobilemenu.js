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
	
	