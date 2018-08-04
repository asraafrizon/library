<!DOCTYPE html>
<html>
<head>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
	<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
	<title>Dashboard Library</title>
</head>
<body>

	<div class="col-sm-5">
		<div class="chart-wrapper">
			<div class="chart-title">
				<strong>Jurnal</strong>
			</div>
			<div class="chart-stage">
				<div id="Jurnal">
					<div class="reset" style="visibility: hidden;">selected: <span class="filter"></span>
						<a href="javascript:JurnalChart.filterAll();dc.redrawAll();">reset</a>
					</div>
				</div>

			</div>

		</div>
	</div>


	<div class="col-sm-5">
		<div class="chart-wrapper">
			<div class="chart-title">
				<strong>Tahun</strong>
			</div>
			<div class="chart-stage">
				<div id="Tahun">
					<div class="reset" style="visibility: hidden;">selected: <span class="filter"></span>
						<a href="javascript:TahunChart.filterAll();dc.redrawAll();">reset</a>
					</div>
				</div>

			</div>

		</div>
	</div>

	<script type="text/javascript" src="{{asset('js/crossfilter.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/d3.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/dc.js')}}"></script>

	<script type="text/javascript">
		
'use strict';
var JurnalChart   = dc.barChart("#Jurnal");
var TahunChart    = dc.barChart("#Tahun");
var dataAll = dc.dataCount('.dataAll');

var links;
d3.json("http://localhost:8000/koleksis", function(error, json) {
	if(error) return console.warn(error);
	links = json;
	console.log(links);
	var numberFormat = d3.format('.2f');

	links.forEach(function(d) {
		d.jurnal = +d.jurnal;
		d.tahun = +d.tahun;
		d.jumlah = +d.jumlah;
	});

	var ndx = crossfilter(links),
	all = ndx.groupAll(),
	tahunDim = ndx.dimension(function(d) {return d.tahun;}),
	jurnalDim = ndx.dimension(function(d) {return d.jurnal;}),
	jumlahDim = ndx.dimension(function(d) {return d.jumlah;}),
	tahunGroup = tahunDim.group().reduceSum(function(d) {return d.jumlah;}),
	jurnalGroup = jurnalDim.group().reduceSum(function(d) {return d.jumlah;});

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

	</script>

</body>
</html>