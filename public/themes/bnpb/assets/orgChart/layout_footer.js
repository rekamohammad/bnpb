OrgChart.prototype.iteratef = function iteratef(source, tree_y, settings) {
	if (source.fchildren == null) return [];
	var nodes = [];
	var tree_x = 0;
	source.fchildren.forEach(function (col) {
		if (col.posisi != null || col.nama != null) {
			col.x = tree_x;
			col.y = tree_y;
			col.id = null;
			col.fparent = source;
			nodes.push(col);
		}
		tree_x += settings.nodeSize[0];
	});
	return nodes;
}

OrgChart.prototype.linksf = function linksf(nodes) {
	var links = [];
	nodes.forEach(function (d) {
		links.push({ source: d.fparent, target: d, elbow_type: 0 });
	});
	return links;
}
