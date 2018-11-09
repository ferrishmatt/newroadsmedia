function DataPush(paramChart, graphType, chartLst) {
	var newChart = paramChart;
	 
	this.doWork = function(jsonData){
//		arrDoWork[graphType](jsonData);
		var sliceCnt = jsonData.stat.length;
		var num = newChart.num;
			for(var j=0; j<sliceCnt; j++){
				for(var i in chartLst){
					var chartName = chartLst[i];
					var seriesOne = newChart.getSeriesLst(chartName);
					seriesOne.addPoint([num, eval("jsonData.stat[j]."+chartName)], true, true);
				}
				newChart.plusNum();
			}
	};
}