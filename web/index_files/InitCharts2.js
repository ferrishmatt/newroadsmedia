function InitCharts(paramChartId, graphType, chartLst) {
  var chartId = paramChartId;
  var seriesLst = new Array();
  this.num = g_totalPoint;
  var setChartType = 'column';
	if(graphType == 'GRAPH_NET')setChartType = 'area';

	var g_seriesFactory = new SeriesFactory();
  var arrSeries = g_seriesFactory.getSeries(chartLst);

	var arrLoadFunc = function() {
  	for(var i in this.series){
			var seriesOne = this.series[i];
			seriesLst[seriesOne.name] = seriesOne;
		}
	};
  
  this.getSeriesLst = function(type){
	  return seriesLst[type];
  	};

  initchart();
    
  this.plusNum = function(){
	  this.num++;
  	};
  
	function initchart(){
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });
	};
	
//	var chart;
    $('#'+chartId).highcharts({

        series: arrSeries,
        chart: {
            type: setChartType,
            animation: Highcharts.svg, // don't animate in old IE
            events: {
                load: 	arrLoadFunc
            }
        },
        title: {
            text: ''
        },
        xAxis: {
            labels: {
                style: {
                    fontSize: '0px',
                         }
                     },
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        yAxis: [{
            title: {
                enabled: false
            }
        }, {
            title: {
                enabled: false
            },
            opposite: true //이게 붙은애는 오른쪽 y축이 된당
         }],
        tooltip: {
            headerFormat: '<table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        legend: {
//            enabled: false
        },
        exporting: {
            enabled: false
        }
    });
}