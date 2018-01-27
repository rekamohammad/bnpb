OrgChart.prototype.Canvas = function (htmlContainer) {
	var self = this;
	// --------------------------------------------------
	// Public Properties
	// --------------------------------------------------
	var pub = {};

	pub.container = htmlContainer || "body";

	pub.dimensions = {
		margin: { top: 20, right: 20, bottom: 20, left: 20 },
		width: $(pub.container).width(),
		height: 800,
		position: $(pub.container + " " + "svg").offset()
	};
	
	function dragstart() {
		d3.select('body').style("cursor", "move");
	}
	
	function dragend() {
		d3.select('body').style("cursor", "auto");
	}

	pub.svg = {
		base: d3.select(pub.container).append("svg")
		   .attr("width", "100%")
		   .attr("height", pub.dimensions.height)
	};

	// Simple zoom by encapsulating in a group and transforming
	pub.svg.transformed = pub.svg.base.append("g");
	
	this.init_x = (pub.dimensions.margin.left + (pub.dimensions.width / 2));
	this.init_y = (pub.dimensions.margin.top + 40);
	this.init_scale = this.current_scale = 0.758;
	
	var zoom = d3.behavior.zoom()
			.on("zoom", onZoom);
	zoom.translate([this.init_x,this.init_y]);
	zoom.scale(this.init_scale);
	var drag = d3.behavior.drag()
		.on("dragstart", dragstart)
		.on("dragend",  dragend);	
	
	function setPanAndZoom(selection) {
		selection.call(zoom);
		selection.call(drag);
	}
	
	pub.svg.transformed.attr("transform",
		"translate(" + this.init_x + "," + this.init_y + ")" + " scale(" + this.init_scale + ")");
								
	pub.svg.base.call(setPanAndZoom);
	pub.svg.base
      .on("mousewheel.zoom", null)
      .on("DOMMouseScroll.zoom", null) // disables older versions of Firefox
      .on("wheel.zoom", null); // disables newer versions of Firefox

	// clip and blur filter
	var defs = pub.svg.transformed.append("defs").attr("id", "imgdefs")

	defs.append('clipPath').attr('id', 'clip-circle')
		.append("circle")
		.attr("r", 43)
		.attr("cx", 43 - 128)
		.attr("cy", 43 - 38);

	var filterCircle = defs.append('filter').attr('id', 'filter-circle')
		.attr('x', '-15px')
		.attr('y', '-15px')
		.attr('width', '116px')
		.attr('height', '116px');
	filterCircle.append("feOffset")
		.attr("result", 'offOut')
		.attr("in", 'SourceAlpha')
		.attr("dx", '0')
		.attr("dy", '0');
	filterCircle.append("feGaussianBlur")
		.attr("result", 'blurOut')
		.attr("in", 'offOut')
		.attr("stdDeviation", '5');
	filterCircle.append("feBlend")
		.attr("in", 'SourceGraphic')
		.attr("in2", 'blurOut')
		.attr("mode", 'normal');
		
	function onZoom() {
		if(d3.event.translate[1] < self.init_y * d3.event.scale) d3.event.translate[1] = self.init_y * d3.event.scale;
		self.current_scale = d3.event.scale;
		d3.select(self.htmlEl).style("height", ((self.max_Y + self.init_y) * self.current_scale) + "px");
		self.canvas.svg.base.attr("height", ((self.max_Y + self.init_y) * self.current_scale));
		pub.svg.transformed.attr("transform",
			   "translate(" + d3.event.translate + ")"
			   + " scale(" + d3.event.scale + ")");
	}

	function updateWindow() {
		// This actually shouldn't change
		pub.dimensions.position =
		   $(pub.container + " " + "svg").offset();

		pub.dimensions.width =
		   $(pub.container).width() - $(".right-sidebar").outerWidth()
		   - $(".left-sidebar").outerWidth() - 20;
		pub.dimensions.height =
		   $(pub.container).height() - $(".header").outerHeight()
		   - $(".footer").outerHeight() - 20;

		pub.svg.base
		   .attr("width", "100%")
		   .attr("height", pub.dimensions.height);
	}
	window.onresize = updateWindow;
	// might want to append as oppose to overwrite the event here

	updateWindow();

	// --------------------------------------------------
	// Public Function
	// --------------------------------------------------
	function remove(container) {
		pub.svg.base.remove();
	}

	// --------------------------------------------------
	// Publicize 
	// --------------------------------------------------
	return pub;
};
