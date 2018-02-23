OrgChart.prototype.iteratet = function iteratet(source, parent_x, parent_y, settings) {
	if (source.tchildren == null) return [];
	var nodes = [];
	var tree_y = parent_y + settings.nodeSize[1];
	source.tchildren.forEach(function (col) {
		if (col.posisi != null || col.nama != null) {
			col.x = parent_x + 40;
			col.y = tree_y;
			col.id = null;
			col.parent = source;
			nodes.push(col);
		}
		tree_y += settings.nodeSize[1] + 20;
	});
	return nodes;
}

OrgChart.prototype.linkst = function linkst(nodes) {
	var links = [];
	nodes.forEach(function (d) {
		links.push({ source: d.parent, target: d, elbow_type: 2 });
	});
	return links;
}
