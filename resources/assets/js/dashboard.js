'use strict';
		var JurnalChart   = dc.barChart("#Jurnal");
		var TahunChart    = dc.barChart("#Tahun");
		var dataAll = dc.dataCount('.dataAll');

		var links = [];
		d3.json("http://localhost:8000/koleks", function(error, json) {
			if(error) return console.warn(error);
			links = json;
			console.log(links);
			var numberFormat = d3.format('.2f');

			// json.forEach(function(d) {
			// 	d.jurnal = +d.jurnal;
			// 	d.tahun = +d.tahun;
			// 	d.jumlah = +d.jumlah;
			// });

			var ndx = crossfilter(links);
			var all = ndx.groupAll();
			var tahunDim = ndx.dimension(function(d) {return d.tahun;});
			var jurnalDim = ndx.dimension(function(d) {return d.jurnal;});
			var jumlahDim = ndx.dimension(function(d) {return d.jumlah;});
			var tahunGroup = tahunDim.group().reduceSum(function(d) {return d.jumlah;});
			var jurnalGroup = jurnalDim.group().reduceSum(function(d) {return d.jumlah;});

			JurnalChart
			.width(480)
			.height(340)
			.dimension(jurnalDim)
			.group(jurnalGroup)
			.controlsUseVisibility(true)
			.x(d3.scale.ordinal())
			.xUnits(dc.units.ordinal)
			.elasticY(true)
			.brushOn (true)
			.renderLabel(true)
			.yAxisLabel(" ");


			TahunChart
			.width(480)
			.height(340)
			.dimension(tahunDim)
			.group(tahunGroup)
			.controlsUseVisibility(true)
			.x(d3.scale.ordinal())
			.xUnits(dc.units.ordinal)
			.elasticY(true)
			.brushOn (true)
			.renderLabel(true)
			.yAxisLabel(" ");

			dataAll /* dc.dataCount('.dc-data-count', 'chartGroup'); */
			.dimension(ndx)
			.group(all)
        // (_optional_) `.html` sets different html when some records or all records are selected.
        // `.html` replaces everything in the anchor with the html given using the following function.
        // `%filter-count` and `%total-count` are replaced with the values obtained.
        .html({
        	some: '<strong>%filter-count</strong> selected out of <strong>%total-count</strong> records' +
        	' | <a href=\'javascript:dc.filterAll(); dc.renderAll();\'>Reset All</a>',
        	all: 'All records selected. Please click on the graph to apply filters.'
        });

        dc.renderAll();

    });