OrgChart.prototype.acumulate_x = function acumulate_x(parent, child, current) {
	if(current === parent) return;
	child.x += current.parent.x;
	current = current.parent;
	if(current.parent === parent) return;
	acumulate_x(parent, child, current);
}

OrgChart.prototype.translate_block_2 = function translate_block_2(parent, translate) {
	parent.x += translate.x;
	parent.y += translate.y;
	if (parent.children) {
		parent.children.forEach(function (d) {
			//d.x += translate.x;
			//d.y += translate.y;
			translate_block_2(d, translate);
		});
	}
	if (parent.gchildren) {
		parent.gchildren.forEach(function (d) {
			//d.x += translate.x;
			//d.y += translate.y;
			translate_block_2(d, translate);
		});
	}
	if (parent.tchildren) {
		parent.tchildren.forEach(function (d) {
			//d.x += translate.x;
			//d.y += translate.y;
			translate_block_2(d, translate);
		});
	}
}

OrgChart.prototype.translate_block = function translate_block(parent, childrens, translate) {
	parent.x += translate.x;
	parent.y += translate.y;
	if (childrens) {
		childrens.forEach(function (d) {
			d.x += translate.x;
			d.y += translate.y;
		});
	}
}
