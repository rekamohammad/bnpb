// (C)2015. PT. Global Data Teknologi
// Do not remove this copyright information!
// ===========================================================

function OrgChart(jsonData, htmlEl, useInternalTree) {
	this.jsonData = jsonData;
	this.htmlEl = htmlEl;
	this.useInternalTree = useInternalTree;

    this.canvas = this.Canvas(htmlEl);

    this.margin = this.canvas.dimensions.margin;
    this.width = this.canvas.dimensions.width - this.margin.right - this.margin.left;
    this.height = this.canvas.dimensions.height - this.margin.top - this.margin.bottom;

    this.svg = this.canvas.svg.transformed;
	
	var self = this;

    d3.json(jsonData, function (error, flare) {
        if (error) throw error;

        self.root = flare;
        self.root.x0 = self.width / 2;
        self.root.y0 = 20;
        self.root.x = 0;
        self.root.y = 0;

        //self.root.children.forEach(collapse);
        self.update(self.root);
    });

    //d3.select(self.frameElement).style("height", "800px");
	d3.select(htmlEl).style("height", "800px");
}

OrgChart.prototype.jsonData = null;
OrgChart.prototype.htmlEl = undefined;
OrgChart.prototype.useInternalTree = undefined;
OrgChart.prototype.i = 0;
OrgChart.prototype.duration = 750;
OrgChart.prototype.root = null;
OrgChart.prototype.canvas = null;
OrgChart.prototype.margin = null;
OrgChart.prototype.width = null;
OrgChart.prototype.height = null;
OrgChart.prototype.diagonal = d3.svg.diagonal().projection(function (d) { return [d.x, d.y]; });
OrgChart.prototype.svg = null;
OrgChart.prototype.min_X = 0;
OrgChart.prototype.max_X = 0;
OrgChart.prototype.max_Y = 0;
OrgChart.prototype.init_x = 0;
OrgChart.prototype.init_y = 0;
OrgChart.prototype.init_scale = 0;
OrgChart.prototype.current_scale = 0;