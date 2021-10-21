/*
 A simple jQuery plugin for creating animated drilldown menus.

 @name jQuery Drilldown
 @version 0.1.2
 @requires jQuery v1.7+
 @author Aleksandras Nelkinas
 @license [MIT]{@link http://opensource.org/licenses/mit-license.php}

 Copyright (c) 2013 Aleksandras Nelkinas
 Creates a mobile menu based on drilldown.js from Aleksandras Nelkinas

  @name Mobile Menu
  @author Johannes Grandy
  @license Do whatever you want

  Copyright (c) 2014 Johannes Grandy

*/
(function(e){"function"===typeof define&&define.amd?define(["jquery"],e):e(jQuery)})(function(e,h){function c(a,b){this._name="drilldown";this._defaults=g;this.element=a;this.$element=e(a);this.options=e.extend({},g,b);this.init()}var g={event:"click",selector:"a",speed:100,cssClass:{container:"drilldown-container",root:"drilldown-root",sub:"drilldown-sub",back:"drilldown-back"}};c.prototype={history:[],css:{"float":"left",width:null},init:function(){var a=this;this.$container=this.$element.find("."+
this.options.cssClass.container);this.$element.on(this.options.event,this.options.selector,function(b){a.handleAction.call(a,b,e(this))})},handleAction:function(a,b){var d=b.nextAll("."+this.options.cssClass.sub),f=!0;d.length?this.down(d):b.closest("."+this.options.cssClass.back).length?this.up():f=!1;f&&"A"===b.prop("tagName")&&a.preventDefault()},down:function(a){var b=this;a.length&&(this.css.width=this.$element.outerWidth(),this.$container.width(2*this.css.width),a=this.$container.find("."+this.options.cssClass.root).first().clone().html(a.html()),
this.$container.append(a),this.animateDrilling(-1*this.css.width,function(){var d=a.prev();b.history.push(d);b.restoreState.call(b,d,a)}))},up:function(){var a=this,b=this.history.pop();this.css.width=this.$element.outerWidth();this.$container.width(2*this.css.width);this.$container.prepend(b);this.animateDrilling(0,function(){var d=b.next();a.restoreState.call(a,d,b)})},animateDrilling:function(a,b){var d=this.$container.children("."+this.options.cssClass.root);d.css(this.css);d.first().animate({"margin-left":a},
this.options.speed,b)},restoreState:function(a,b){b.css({"float":"none",width:"auto"});a.remove();this.$container.width("auto")}};e.fn.drilldown=function(a){return this.each(function(){e.data(this,"drilldown")||e.data(this,"drilldown",new c(this,a))})}});
(function(e){var h={init:function(c){jQuery.fn.mobilemenu.settings=e.extend({},jQuery.fn.mobilemenu.settings,c);var g=jQuery.fn.mobilemenu.settings;c=this.clone();c.addClass("mobilemenu_drilldownmenu").find("li").each(function(){var f=jQuery(this),k=f.children("ul"),l=f.children("a").text(),m=f.children("a").attr("href");k.addClass("mobilemenu_drilldownsubmenu").hide();0<k.length&&(f.addClass("mobilemenu_drilldowndeeper"),g.cloneParents&&k.prepend('<li class="mobilemenu_drilldownparent"><a href="'+
m+'">'+l+"</a></li>"),jQuery('<li class="mobilemenu_drilldownback" style="position: relative;"><a href="#">'+g.drilldownBack+"</a></li>").prependTo(k))});var a=jQuery('<div id="mobilemenu_overlay"></div>').appendTo("body"),b=jQuery('<div id="mobilemenu_slide"></div>').appendTo("body");b=jQuery('<div id="mobilemenu_main"></div>').appendTo(b);var d=jQuery('<div class="mobilemenu_drilldown"></div>').appendTo(b);c.appendTo(d);b.drilldown({event:"click",selector:"a",speed:g.drilldownAnimationSpeed,cssClass:{container:"mobilemenu_drilldown",
root:"mobilemenu_drilldownmenu",sub:"mobilemenu_drilldownsubmenu",back:"mobilemenu_drilldownback"}});a.on("click",function(f){fvw.hash.remove()});jQuery(window).bind("hashchange load",function(f){!1!==fvw.hash.beginsWidth("mobilemenu")?jQuery.fn.mobilemenu("open"):jQuery.fn.mobilemenu("close")});g.onCreate(g)},open:function(){var c=jQuery.fn.mobilemenu.settings;jQuery("body").addClass("mobilemenu_active");c.onOpen(this,c)},close:function(){var c=jQuery.fn.mobilemenu.settings;jQuery("body").removeClass("mobilemenu_active");
c.onClose(this,c)}};jQuery.fn.mobilemenu=function(c){if(h[c])return h[c].apply(this,Array.prototype.slice.call(arguments,1));if("object"!==typeof c&&c)jQuery.error("Method "+c+" does not exist on jQuery.mobilemenu");else return h.init.apply(this,arguments)};jQuery.fn.mobilemenu.settings={closeOnUnload:!0,cloneParents:!0,drilldownBack:fvwFramework.mobilemenuBack,drilldownAnimationSpeed:130,onCreate:function(){},onOpen:function(){},onClose:function(){}}})(jQuery);