function GetChartData(){
	var from = 0;
	var updateState = new UpdateState();
	
	this.getData = function() {
				$.ajax({
					type:'GET',
					url : 'getData.do',
					dataType:'json',
				 	async: false,  
					data : {"from": from},
					success:function(jsonData,textStatus){					
							from = jsonData.last;
							var ok = jsonData.ok;
							if(ok==0){
								console.log();
								var errMsg = jsonData.errmsg;
								var msg = errMsg==''?'Server is not responding.':errMsg;
								Index.initIntroNoduplication("MongoDB server returned error information for request.", msg+"\n Time : "+getTimeStrFromDateObjectUSA(new Date(from)));
							}else{
								$('#date_area').empty().text(getDateStrFromDateObjectUSA(new Date(from)));
								updateState.setJsonData(jsonData);
								updateState.updateMajorState();
								updateState.updateList();
								var arrDataPush = g_graphManager.getGraphLst();
								for(var i in arrDataPush){
									arrDataPush[i].doWork(jsonData);
								}
							}
			    		},
			    	error:function(xhr,textStatus, errorThrown){
			    		}
				});
	};
}