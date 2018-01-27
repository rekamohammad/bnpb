OrgChart.prototype.iterateh = function iterateh(source, tree_y, settings) {
	if (source.children == null) return [];
	var nodes = [];
	var tree_x = 0;
	source.children.forEach(function (col) {
		if (col.posisi != null || col.nama != null) {
			col.x = tree_x;
			col.y = tree_y;
			col.id = null;
			col.parent = source;
			nodes.push(col);
		}
		tree_x += settings.nodeSize[0];
	});
	return nodes;
}

OrgChart.prototype.linksh = function linksh(nodes) {
	var links = [];
	nodes.forEach(function (d) {
		links.push({ source: d.parent, target: d, elbow_type: 0 });
	});
	return links;
}
