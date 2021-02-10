/*--------------------------------------------------------------------------------
 * console.logが利用できないブラウザ対応
 *--------------------------------------------------------------------------------*/
if (typeof window.console != 'object'){
 window.console = {log:function(){},debug:function(){},info:function(){},warn:function(){},error:function(){},assert:function(){},dir:function(){},dirxml:function(){},trace:function(){},group:function(){},groupEnd:function(){},time:function(){},timeEnd:function(){},profile:function(){},profileEnd:function(){},count:function(){}};
}

OpenLayers.Control.Crosshairs = OpenLayers.Class(OpenLayers.Control, {
	
	imgUrl: null,
	size: null,
	position: null,
    autoActivate: true,


	initialize: function(options) {
		OpenLayers.Control.prototype.initialize.apply(this, arguments);
	},

	draw: function() {
		OpenLayers.Control.prototype.draw.apply(this, arguments);
		var px = this.position.clone();
		var centered = new OpenLayers.Pixel(Math.round(px.x - (this.size.w / 2)), Math.round(px.y - (this.size.h / 2)));
		this.buttons = new Array();
		this.div = OpenLayers.Util.createAlphaImageDiv(
				OpenLayers.Util.createUniqueID("OpenLayers.Control.Crosshairs_"), 
				centered, 
				this.size, 
				this.imgUrl, 
				"absolute");

		this.map.events.register( 'zoomend', this, this.redraw );
//		this.map.events.register( 'resize', this, this.redraw );

		return this.div;
	},
	
	setPosition: function(position) {
		this.position = position;
		var px = this.position.clone();
		var centered = new OpenLayers.Pixel(Math.round(px.x - (this.size.w / 2)), Math.round(px.y - (this.size.h / 2)));
		this.div.style.left = centered.x + "px";
		this.div.style.top  = centered.y + "px";
	},
	
	redraw: function() {
		var px = this.map.getSize();
		
		var centered = new OpenLayers.Pixel(Math.round(px.w/2 - (this.size.w / 2)), Math.round(px.h/2 - (this.size.h / 2)));
		this.div.style.left = centered.x + "px";
		this.div.style.top  = centered.y + "px";
    },
	
	CLASS_NAME: "OpenLayers.Control.Crosshairs"
});