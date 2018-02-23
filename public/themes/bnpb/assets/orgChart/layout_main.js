OrgChart.prototype.iterateAll = function iterateAll(source, nodelinksobj, settings) {
	var self = this;
	var nodes = [];
	var links = [];

	var src_x = source.x;
	var src_y = source.y;
	if (source === this.root) {
		source.x = 0;
		source.y = 0;
		nodes = nodes.concat(source);
	}

	var gnodes = [];
	var glinks = [];

	var max_X = 0;
	var max_Y = 0;
	if (source.gchildren) {
		var grid_y = 0;
		var grid_block_nodes = [];
		var grid_block_links = [];
		source.gchildren.forEach(function (row) {
			grid_block_nodes = self.giteraterow(source, row, grid_y, settings);
			grid_block_links = self.linksg(grid_block_nodes);

			grid_block_nodes.forEach(function (d) {
				if (max_X < d.x) max_X = d.x;
			});
			var adjust_X = max_X / 2;
			grid_block_nodes.forEach(function (d, i) {
				d.y = d.y + source.y + settings.nodeSize[1];
				d.x = d.x - adjust_X;
				if (i < grid_block_nodes.length / 2) { d.gridpos = "left"; }
				else { d.gridpos = "right"; }
			});
			var row_childs_max_y = 0;
			var row_childs_nodes = [];
			var row_childs_links = [];
			grid_block_nodes.forEach(function (d) {

				var grid_block_childs = self.iterateAll(d, nodelinksobj, settings);

				row_childs_nodes = row_childs_nodes.concat(grid_block_childs.nodes);
				row_childs_links = row_childs_links.concat(grid_block_childs.links);

				if (grid_block_childs.nodes.length > 0) {

					var block_childs_max_x = 0;
					var block_childs_min_x = Number.MAX_VALUE;
					grid_block_childs.nodes.forEach(function (d) {
						if (row_childs_max_y < d.y) row_childs_max_y = d.y;
						if (block_childs_max_x < d.x) block_childs_max_x = d.x;
						if (block_childs_min_x > d.x) block_childs_min_x = d.x;
					});
					var block_childs_adjust_x = (block_childs_max_x - block_childs_min_x) / 2;
					grid_block_childs.nodes.forEach(function (d) {
						if (d.gridpos === undefined)
							d.x = d.x - block_childs_adjust_x;
					});
					d.x = 0;
					grid_block_childs.nodes.forEach(function (d) {
						if (block_childs_min_x > d.x) block_childs_min_x = d.x;
					});
					if (d.gridpos == "right") {
						if (block_childs_min_x < source.x) {
							self.translate_block(d, grid_block_childs.nodes, { x: (source.x - block_childs_min_x + (settings.nodeSize[0] / 2)), y: 0 })
						}
					}
					if (d.gridpos == "left") {
						if (true) {
							self.translate_block(d, grid_block_childs.nodes, { x: source.x - (block_childs_max_x - block_childs_min_x), y: 0 })
						}
					}
				}
			});
			gnodes = gnodes.concat(row_childs_nodes);
			gnodes = gnodes.concat(grid_block_nodes);
			glinks = glinks.concat(row_childs_links);
			glinks = glinks.concat(grid_block_links);

			if (grid_y + settings.nodeSize[1] < row_childs_max_y)
				grid_y = row_childs_max_y;
			else
				grid_y += settings.nodeSize[1];
		});
	}

	gnodes.forEach(function (d) {
		if (max_Y < d.y) max_Y = d.y;
	});

	if(source.y > max_Y) max_Y = source.y;
	
	var tnodes = [];
	var tlinks = [];
	if (source.tchildren) {
		var tree_y = 0;
		tnodes = self.iteratet(source, 20, max_Y + 50, settings);
		tlinks = self.linkst(tnodes);
		var tree_childs_nodes = [];
		var tree_childs_links = [];
		var childs_max_y = source.y;
		tnodes.forEach(function (d) {
			if(childs_max_y < d.y) childs_max_y = d.y;
			self.translate_block(d, [], { x: 0, y: childs_max_y - d.y })
			var tree_node_childs = self.iterateAll(d, nodelinksobj, settings);
			tree_childs_nodes = tree_childs_nodes.concat(tree_node_childs.nodes);
			tree_childs_links = tree_childs_links.concat(tree_node_childs.links);
			tree_childs_nodes.forEach(function (child_node) {
				if(childs_max_y < child_node.y) childs_max_y = child_node.y;
			});
			childs_max_y += settings.nodeSize[1] + 20;
		});
		tnodes = tnodes.concat(tree_childs_nodes);
		tlinks = tlinks.concat(tree_childs_links);
	}
	
	var hnodes = [];
	var hlinks = [];
	if (source.children) {
		hnodes = self.iterateh(source, max_Y + settings.nodeSize[1] + 50, settings);
		hlinks = self.linksh(hnodes);

		var treemax_X = 0;
		hnodes.forEach(function (d) {
			if (max_X < d.x) max_X = d.x;
			if (treemax_X < d.x) treemax_X = d.x;
		});
		var adjusttree_X = treemax_X / 2;
		var adjustmax_X = max_X / 2;

		hnodes.forEach(function (d, i) {
			d.x = d.x - adjusttree_X;
			if (i+1 < (hnodes.length+1) / 2) { d.treepos = "left"; }
			else if (i+1 > (hnodes.length+1) / 2) { d.treepos = "right"; }
			else { d.treepos = "middle"; }
		});

		var tree_childs_nodes = [];
		var tree_childs_links = [];
		hnodes.forEach(function (d) {
			var tree_node_childs = self.iterateAll(d, nodelinksobj, settings);
			tree_childs_nodes = tree_childs_nodes.concat(tree_node_childs.nodes);
			tree_childs_links = tree_childs_links.concat(tree_node_childs.links);
			var tree_childs_max_x = -Number.MAX_VALUE;
			var tree_childs_min_x = Number.MAX_VALUE;
			var tree_childs_width = 0;
		    tree_node_childs.nodes.forEach(function (c) {
			   self.acumulate_x(d,c,c);
			});
			tree_node_childs.nodes.forEach(function (c) {
				if (c.x < tree_childs_min_x) tree_childs_min_x = c.x;
				if (c.x > tree_childs_max_x) tree_childs_max_x = c.x;
			});
			tree_childs_width = (tree_childs_max_x - tree_childs_min_x)
			d.tree_width = tree_childs_width;
		});
		var tree_middle_adjust = 0;
		hnodes.forEach(function (d) {
			if (d.treepos == "middle") {
				if (d.children) {
					tree_middle_adjust += (d.tree_width / 2);
				}
			}
		});
		var tree_right_adjust = 0;
		hnodes.forEach(function (d) {
			if (d.treepos == "right") {
				//translate_block_2(d, { x: tree_right_adjust + tree_middle_adjust, y: 0 })
				if (d.children || d.tchildren) {
					tree_right_adjust += (d.tree_width / 2);
				}
				self.translate_block_2(d, { x: tree_right_adjust + tree_middle_adjust, y: 0 })
			}
		});
		var tree_left_adjust = 0;
		if(hnodes.reverse().filter(function (d) { return (d.treepos == "left");})[0].tchildren){
			tree_left_adjust = -40;
		}
		hnodes.reverse().forEach(function (d) {
			if (d.treepos == "left") {
				//translate_block_2(d, { x: tree_left_adjust - tree_middle_adjust, y: 0 })
				if (d.children || d.tchildren) {
					tree_left_adjust -= (d.tree_width / 2);
				}
				self.translate_block_2(d, { x: tree_left_adjust + tree_middle_adjust, y: 0 })
			}
		});

		hnodes = hnodes.concat(tree_childs_nodes);
		hlinks = hlinks.concat(tree_childs_links);
	}
	
	hnodes.forEach(function (d) {
		if (max_Y < d.y) max_Y = d.y;
	});
	
	var fnodes = [];
	var flinks = [];
	if (source.fchildren) {
		var grid_y = 0;
		var grid_block_nodes = [];
		var grid_block_links = [];
		source.fchildren.forEach(function (row) {
			grid_block_nodes = self.giteraterow(source, row, grid_y, settings);
			grid_block_links = self.linksg(grid_block_nodes);

			max_X = 0;
			grid_block_nodes.forEach(function (d) {
				if (max_X < d.x) max_X = d.x;
			});
			var adjust_X = max_X / 2;
			grid_block_nodes.forEach(function (d, i) {
				d.y = d.y + max_Y + settings.nodeSize[1] + 50;
				d.x = d.x - adjust_X;
				if ( i+1 < (grid_block_nodes.length+1)/2 ) { d.gridpos = "left"; }
				else if ( i+1 > (grid_block_nodes.length+1)/2 ) { d.gridpos = "right"; }
				else { d.gridpos = "middle"; }
			});
			var row_childs_max_y = 0;
			var row_childs_nodes = [];
			var row_childs_links = [];
			grid_block_nodes.forEach(function (d) {

				var grid_block_childs = self.iterateAll(d, nodelinksobj, settings);

				row_childs_nodes = row_childs_nodes.concat(grid_block_childs.nodes);
				row_childs_links = row_childs_links.concat(grid_block_childs.links);

				if (grid_block_childs.nodes.length > 0) {

					var block_childs_max_x = 0;
					var block_childs_min_x = Number.MAX_VALUE;
					grid_block_childs.nodes.forEach(function (d) {
						if (row_childs_max_y < d.y) row_childs_max_y = d.y;
						if (block_childs_max_x < d.x) block_childs_max_x = d.x;
						if (block_childs_min_x > d.x) block_childs_min_x = d.x;
					});
					var block_childs_adjust_x = (block_childs_max_x - block_childs_min_x) / 2;
					grid_block_childs.nodes.forEach(function (d) {
						if (d.gridpos === undefined)
							d.x = d.x - block_childs_adjust_x;
					});
					d.x = 0;
					grid_block_childs.nodes.forEach(function (d) {
						if (block_childs_min_x > d.x) block_childs_min_x = d.x;
					});
					if (d.gridpos == "right") {
						if (block_childs_min_x < source.x) {
							self.translate_block(d, grid_block_childs.nodes, { x: (source.x - block_childs_min_x + (settings.nodeSize[0] / 2)), y: 0 })
						}
					}
					if (d.gridpos == "left") {
						if (true) {
							self.translate_block(d, grid_block_childs.nodes, { x: source.x - (block_childs_max_x - block_childs_min_x), y: 0 })
						}
					}
				}
			});
			gnodes = gnodes.concat(row_childs_nodes);
			gnodes = gnodes.concat(grid_block_nodes);
			glinks = glinks.concat(row_childs_links);
			glinks = glinks.concat(grid_block_links);

			if (grid_y + settings.nodeSize[1] < row_childs_max_y)
				grid_y = row_childs_max_y;
			else
				grid_y += settings.nodeSize[1];
		});
	}
	
	var min_X = 0;
	gnodes.forEach(function (d) {
		if (min_X > d.x) min_X = d.x;
		if (max_X < d.x) max_X = d.x;
		if (max_Y < d.y) max_Y = d.y;
	});
	this.min_X = min_X - settings.nodeSize[0];
	this.max_X = max_X + settings.nodeSize[0];
	this.max_Y = max_Y + settings.nodeSize[1];
	d3.select(this.htmlEl).style("height", ((this.max_Y + this.init_y) * this.current_scale) + "px");
	this.canvas.svg.base.attr("height", ((this.max_Y + this.init_y) * this.current_scale));

	nodes = nodes.concat(gnodes);
	links = links.concat(glinks);
	nodes = nodes.concat(tnodes);
	links = links.concat(tlinks);
	nodes = nodes.concat(hnodes);
	links = links.concat(hlinks);

	return { nodes: nodes.concat(nodelinksobj.nodes), links: links.concat(nodelinksobj.links) };
}

