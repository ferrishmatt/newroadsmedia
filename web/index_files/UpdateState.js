function UpdateState(){
	
	var jsonData;
	var listData = [];
	this.setJsonData = function(paramJsonData) {
		jsonData = paramJsonData;
	};
	
	this.updateMajorState = function() {
		for(var i in arrMajorState){
			var majorStateName = arrMajorState[i];
			var state = 0;
			if(jsonData.stat[0] != undefined)state = eval("jsonData.stat[0]."+majorStateName);
			$('#major_'+majorStateName).empty().text(state==null?0 : state);
		}
	};

	this.updateList = function() {
		if(jsonData.stat[0] != undefined){
			var arrTableHtml = new Array();
			if(listData.length>= g_tablePoint)listData = listData.slice(1);
			listData.push(jsonData.stat[0]);
			for(var i in listData){
				arrTableHtml.push('<tr>');
			  var arr = arrTableState.another;
			  if(g_process == 'mongos')arr = arrTableState.mongos;
				for(var j in arr){
					if(arr[j] == 'time'){
						arrTableHtml.push('<td style="padding-right:10px;">'+getTimeStrFromDateObjectUSA(new Date(eval('listData[i].'+arr[j])))+'</td>');
					}else{
						arrTableHtml.push('<td>'+addCommas(eval('listData[i].'+arr[j]))+'</td>');
					}
				}
				arrTableHtml.push('</tr>');
			}
			  $('#mongostateTableBody').empty().html(arrTableHtml.join("\n"));
		}
	};

	function addCommas(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;;
	}
}