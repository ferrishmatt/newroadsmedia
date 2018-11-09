function SeriesFactory() {
	var arrSeries = new Array();
	 
	this.getSeries = function(seriesType){
		for(var i in seriesType){
			if(i == 0){
				arrSeries.push("[{");
			}else{
				arrSeries.push(",{");
			}
			arrSeries.push("name: '"+seriesType[i]+"',");
			if(seriesType[i] == 'faults' || seriesType[i] == 'getmore')arrSeries.push("type: 'spline', yAxis: 1,");
//			if(seriesType[i] == 'netIn')arrSeries.push("color: '#89A54E',"); // 그래프 라인 색 지정
			arrSeries.push("data: (function() {");
			arrSeries.push("var data = [],i;");
			arrSeries.push("for (i = 0; i < g_totalPoint; i++) {");
			arrSeries.push("data.push({");
			arrSeries.push("x: i,");
			arrSeries.push("y: 0");
			arrSeries.push(" });");
			arrSeries.push(" }");
			arrSeries.push("return data;");
			arrSeries.push(" })()");
			arrSeries.push(" }");
			if(i == seriesType.length-1)arrSeries.push("]");
		}
		return eval(arrSeries.join("\n"));
	};
}