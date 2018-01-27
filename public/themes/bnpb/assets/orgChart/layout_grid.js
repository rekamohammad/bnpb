OrgChart.prototype.giteraterow = function giteraterow(source, row, grid_y, settings) {
	var nodes = [];
	var grid_x = 0;
	// col
	row.forEach(function (col) {
		if (col.posisi != null || col.nama != null) {
			col.x = grid_x;
			col.y = grid_y;
			col.id = null;
			col.parent = source;
			nodes.push(col);
		}
		grid_x += settings.nodeSize[0];
	});
	return nodes;
}

OrgChart.prototype.iterateg = function iterateg(source, settings) {
	if (source.gchildren == null) return [];
	var nodes = [];
	var grid_y = 0;
	// row
	source.gchildren.forEach(function (row) {
		var grid_x = 0;
		// col
		row.forEach(function (col) {
			if (col.posisi != null || col.nama != null) {
				col.x = grid_x;
				col.y = grid_y;
				col.id = null;
				col.parent = source;
				nodes.push(col);
			}
			grid_x += (settings.nodeSize[0] * 2);
		});
		grid_y += settings.nodeSize[1];
	});
	return nodes;
}

OrgChart.prototype.linksg = function linksg(nodes) {
	var links = [];
	nodes.forEach(function (d) {
		links.push({ source: d.parent, target: d, elbow_type: 1 });
	});
	return links;
}
