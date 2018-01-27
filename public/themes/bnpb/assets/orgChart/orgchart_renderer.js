OrgChart.prototype.centered = null;

OrgChart.prototype.update = function update(source, expand) {
	var self = this;
	var nodes, links;
	if (this.useInternalTree !== undefined) {
		var tree = d3.layout.tree().nodeSize([260, 150]);
		nodes = tree.nodes(this.root).reverse();
		links = tree.links(nodes);
	} else {
		var nodelinksobj = this.iterateAll(this.root, { nodes: [], links: [] }, { nodeSize: [300, 100] });

		nodes = nodelinksobj.nodes;
		links = nodelinksobj.links;
	}

	// From here zoom to expanded node =======
	/*var x = 0,
		y = 0;

	// If the click was on the centered state or the background, re-center.
	// Otherwise, center the clicked-on state.
	if (!source) {
		centered = null;
	} else {
		x = source.x - (canvas.dimensions.width/2);
		y = source.y - 50;
		centered = source;
	}

	// Transition to the new transform.
	svg.transition()
		.duration(750)
		.attr("transform", "translate(" + -x + "," + -y + ")");*/
	// To here =================================


	// Update the nodes…
	var node = this.svg.selectAll("g.node");
	node = node.data(nodes, function (d) { return d.id || (d.id = ++self.i); });

	// Enter any new nodes at the parent's previous position.
	var nodeEnter = node.enter().append("g")
		.attr("class", "node").style("fill-opacity", 0)
		.attr("transform", function (d) { return "translate(" + source.x0 + "," + source.y0 + ")"; })
		.on("click", function(d){ self.click(self, d); });

		
	nodeEnter.filter(function (d) { return d.posisi == "UPT"; }).append("path")
		.attr("d", this.roundedRect(-100, -28, 220, 86, 5))
		.style("fill", "#aaa");
	nodeEnter.filter(function (d) { return d.posisi == "UPT"; }).append("path")
		.attr("d", this.roundedRect(-105, -33, 220, 86, 5))
		.style("fill", "#bbb");
	nodeEnter.filter(function (d) { return d.nama != null && d.posisi == "UPT"; }).append("path")
		.attr("d", this.roundedRect(-110, -38, 220, 86, 5))
		.style("fill", "#ccc");
		
		
	nodeEnter.filter(function (d) { return d.nama != null && d.posisi != "UPT"; }).append("path")
		.attr("d", this.roundedRect(-110, -38, 220, 86, 5))
		.style("fill", "#ccc");
	var posisiPath = nodeEnter.append("path").style("fill", function (d) { return d.bg !== undefined ? d.bg : "#77a0d2"; });
	posisiPath.filter(function (d) { return d.nama != null; })
		.attr("d", this.roundedRect(-105, -33, 210, 30, 5));
	posisiPath.filter(function (d) { return d.nama == null; })
		.attr("d", this.roundedRect(-110, -38, 220, 86, 5));
	
	
	var expand_button = node
		.filter(function (d) { return (d._gchildren !== undefined && d._gchildren != null)
								   || (d._children !== undefined && d._children != null)
								   || (d._tchildren !== undefined && d._tchildren != null); })
		.append("path")
	expand_button
		.attr("d", this.roundedRect(-12, 52, 20, 20, 2))
		.attr("class", "expand_button")
		.style("fill", "#fafafa");
	var expand_button_plus = node
		.filter(function (d) { return (d._gchildren !== undefined && d._gchildren != null) 
								   || (d._children !== undefined && d._children != null)
								   || (d._tchildren !== undefined && d._tchildren != null); })
		.append("path")
	expand_button_plus
		.attr("d", "M0,56 v13 M-6,62 h13")
		.attr("class", "expand_button");
		
	node.filter(function (d) { 
		return d._gchildren == null && d._children == null && d._tchildren == null; 
	}).selectAll(".expand_button").remove();
	
		
	var pictAvailable = nodeEnter.filter(function (d) { return d.pict !== undefined; });
	pictAvailable.append("circle")
		.attr("r", 43)
		.attr("cx", 43 - 128)
		.attr("cy", 43 - 38)
		.style("fill", "#ccc")
		.style("fill-opacity", 0)
		.style("stroke-opacity", 0)
		.attr("filter", "url(#filter-circle)");
	pictAvailable.append("image")
		.attr("xlink:href", function (d) { return d.pict; })
		.attr("x", -128)
		.attr("y", -38)
		.attr("width", 86)
		.attr("height", 86)
		.style("opacity", 0)
		.attr("clip-path", "url(#clip-circle)");

	nodeEnter.filter(function (d) { return d.posisi != "UPT"; }).append("text")
		.attr("x", function (d) { return 100; })
		.attr("y", function (d) { return -25; })
		.attr("dy", ".35em")
		.attr("text-anchor", function (d) { return "end"; })
		.text(function (d) { return d.posisi; })
		.style("fill-opacity", 0)
		.style("stroke-opacity", 0)
		.call(this.wrap, 160);
		
	nodeEnter.filter(function (d) { return d.posisi == "UPT"; }).append("text")
		.attr("x", function (d) { return 100; })
		.attr("y", function (d) { return -25; })
		.attr("dy", ".35em")
		.attr("text-anchor", function (d) { return "end"; })
		.text(function (d) { return d.posisi; })
		.style("fill-opacity", 0)
		.style("stroke-opacity", 0)
		.call(this.wrap, 160);
	
	nodeEnter.filter(function (d) { return d.nama != null; }).append("text")
		.attr("x", function (d) { return 105; })
		.attr("y", function (d) { return 5; })
		.attr("dy", ".35em")
		.attr("text-anchor", function (d) { return "end"; })
		.text(function (d) { return d.nama; })
		.style("fill-opacity", 1e-6)
		.call(this.wrapBold, 155);
	nodeEnter.filter(function (d) { return d.jabatan != null; }).append("text")
		.attr("x", function (d) { return 105; })
		.attr("y", function (d) { return 18; })
		.attr("dy", ".35em")
		.attr("text-anchor", function (d) { return "end"; })
		.text(function (d) { return d.jabatan; })
		.style("fill-opacity", 1e-6)
		.call(this.wrap, 150);

	// Transition nodes to their new position.
	var nodeUpdate = node.transition()
		.duration(this.duration).style("fill-opacity", 1)
		.attr("transform", function (d) { return "translate(" + d.x + "," + d.y + ")"; });

	nodeUpdate.selectAll("text")
		.style("stroke-opacity", 1)
		.style("fill-opacity", 1);

	nodeUpdate.selectAll("circle")
		.style("fill-opacity", 1)
		.style("stroke-opacity", 1);

	nodeUpdate.selectAll("image")
		.style("opacity", 1);
		
	// Transition exiting nodes to the parent's new position.
	var nodeExit = node.exit().transition()
		.duration(this.duration)
		.attr("transform", function (d) { return "translate(" + source.x + "," + source.y + ")"; })
		.style("fill-opacity", 0)
		.remove();

	nodeExit.selectAll("image")
		.style("opacity", 0);

	nodeExit.selectAll("circle")
		.style("fill-opacity", 0)
		.style("stroke-opacity", 0);

	nodeExit.selectAll("text")
		.style("stroke-opacity", 0)
		.style("fill-opacity", 0);

	//nodeExit.select("circle")
	//    .attr("r", 1e-6);

	//nodeExit.select("text")
	//    .style("fill-opacity", 1e-6);

	// Update the links…
	var link = this.svg.selectAll("path.link")
		.data(links, function (d) { return d.target.id; });

	// Enter any new links at the parent's previous position.
	link.enter().insert("path", "g")
		.attr("class", "link")
		.attr("d", function (d) {
			var o = { x: source.x0, y: source.y0 };
			return self.elbow({ source: o, target: o, elbow_type: d.elbow_type });
		});

	// Transition links to their new position.
	link.transition()
		.duration(this.duration)
		.attr("d", this.elbow);

	// Transition exiting nodes to the parent's new position.
	link.exit().transition()
		.duration(this.duration)
		.attr("d", function (d) {
			var o = { x: source.x, y: source.y };
			return self.elbow({ source: o, target: o, elbow_type: d.elbow_type });
		})
		.remove();

	// Stash the old positions for transition.
	nodes.forEach(function (d) {
		d.x0 = d.x;
		d.y0 = d.y;
	});
}

OrgChart.prototype.roundedRect = function roundedRect(x, y, width, height, radius) {
	return "M" + (x + radius) + "," + y
		 + "h" + (width - radius)
		 + "a" + radius + "," + radius + " 0 0 1 " + radius + "," + radius
		 + "v" + (height - 2 * radius)
		 + "a" + radius + "," + radius + " 0 0 1 " + -radius + "," + radius
		 + "h" + (2 * radius - width)
		 + "a" + radius + "," + radius + " 0 0 1 " + -radius + "," + -radius
		 + "v" + -(height - 2 * radius)
		 + "a" + radius + "," + radius + " 0 0 1 " + radius + "," + -radius
		 + "z";
}

OrgChart.prototype.elbow = function elbow(d) {
	if (d.elbow_type === undefined || d.elbow_type == 0) {
		var startV = d.target.y - 70;
		if (startV < d.source.y) startV = d.source.y;
		return "M" + d.source.x + "," + d.source.y
		  + "V" + startV + "H" + d.target.x + "V" + d.target.y;
	} else if (d.elbow_type == 1) {
		 return "M" + d.source.x + "," + d.source.y
		  + "V" + d.target.y + "H" + d.target.x;
	} else {
		return "M" + d.source.x + "," + d.source.y
		  + "v" + 80 + "H" + (d.target.x - 150)
		  + "V" + d.target.y + "H" + d.target.x;
	}
}

OrgChart.prototype.wrap = function wrap(text, width) {
	text.each(function () {
		var text = d3.select(this),
			words = text.text().split(/\s+/).reverse(),
			word,
			line = [],
			lineNumber = 0,
			lineHeight = 1.1, // ems
			x = text.attr("x"),
			y = text.attr("y"),
			dy = parseFloat(text.attr("dy")),
			tspan = text.text(null).append("tspan").attr("x", x).attr("y", y).attr("dy", dy + "em");
		while (word = words.pop()) {
			if (word.indexOf("<br>") > -1) {
				var arrWord = word.split("<br>");
				line.push(arrWord[0]);
				tspan.text(line.join(" "));
				line = [arrWord[1]];
				tspan = text.append("tspan").attr("x", x).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(arrWord[1]);
			} else {
				line.push(word);
				tspan.text(line.join(" "));
				if (tspan.node().getComputedTextLength() > width) {
					line.pop();
					tspan.text(line.join(" "));
					line = [word];
					tspan = text.append("tspan").attr("x", x).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
				}
			}
		}
	});
}

OrgChart.prototype.wrapBold = function wrapBold(text, width) {
	text.each(function () {
		var text = d3.select(this),
			words = text.text().split(/\s+/).reverse(),
			word,
			line = [],
			lineNumber = 0,
			lineHeight = 1.1, // ems
			x = text.attr("x"),
			y = text.attr("y"),
			dy = parseFloat(text.attr("dy")),
			tspan = text.text(null).append("tspan").attr("x", x).attr("y", y).attr("dy", dy + "em").attr("style", "font-weight: bold;");
		while (word = words.pop()) {
			line.push(word);
			tspan.text(line.join(" "));
			if (tspan.node().getComputedTextLength() > width) {
				line.pop();
				tspan.text(line.join(" "));
				line = [word];
				tspan = text.append("tspan").attr("x", x).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word).attr("style", "font-weight: bold;");
			}
		}
	});
}
