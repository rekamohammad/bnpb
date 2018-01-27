OrgChart.prototype.click = function click(self, d) {
	var expand = false;
	if (d.children) {
		if (d.children !== undefined) {
			d._children = d.children;
			d.children = null;
		}
	} else {
		if (d._children !== undefined) {
			d.children = d._children;
			d._children = null;
			expand = true;
		}
	}
	if (d.gchildren) {
		if (d.gchildren !== undefined) {
			d._gchildren = d.gchildren;
			d.gchildren = null;
		}
	} else {
		if (d._gchildren !== undefined) {
			d.gchildren = d._gchildren;
			d._gchildren = null;
			expand = true;
		}
	}
	if (d.tchildren) {
		if (d.tchildren !== undefined) {
			d._tchildren = d.tchildren;
			d.tchildren = null;
		}
	} else {
		if (d._tchildren !== undefined) {
			d.tchildren = d._tchildren;
			d._tchildren = null;
			expand = true;
		}
	}
	if (d.fchildren) {
		if (d.fchildren !== undefined) {
			d._fchildren = d.fchildren;
			d.fchildren = null;
		}
	} else {
		if (d._fchildren !== undefined) {
			d.fchildren = d._fchildren;
			d._fchildren = null;
			expand = true;
		}
	}
	self.update(d, expand);
}

OrgChart.prototype.collapse = function collapse(d) {
	if (d.children) {
		d._children = d.children;
		d._children.forEach(collapse);
		d.children = null;
	}
}
