function GraphManager(){
	var arrDataPush = new Array();
	
	this.makeGraphLst = function(){
		arrDataPush = new Array();
		for(var i in arrNameLst){
			var constsName = arrNameLst[i];
			var constsLst = eval("arrChartLst."+constsName);
			arrDataPush[constsName] = new DataPush(new InitCharts(constsName+'_container', constsName, constsLst), constsName, constsLst);
		}
	};
	
	this.getGraphLst = function(){
		return arrDataPush;
	};
}