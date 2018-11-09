var Index = function () {

	var isIntroMsg = false;
    return {
    	//알림 한개만 생성 
        initIntroNoduplication: function (titleParam, textParam) {
        			if(isIntroMsg == false){
            			isIntroMsg = true;
    	            var unique_id = $.gritter.add({
    	                // (string | mandatory) the heading of the notification
    	                title: titleParam,
    	                // (string | mandatory) the text inside the notification
    	                text: textParam,
    	                // (string | optional) the image to display on the left
    	                image: './assets/img/warning_sign.png',
    	                // (bool | optional) if you want it to fade out on its own or just sit there
    	                sticky: true,
    	                // (int | optional) the time you want it to be alive for before fading out
    	                time: '',
    	                // (string | optional) the class name you want to apply to that specific message
    	                class_name: 'my-sticky-class'
        	            });
		    	            // You can have it return a unique id, this can be used to manually remove it later using
		    	            setTimeout(function () {
		    	            			isIntroMsg = false;
		    	                $.gritter.remove(unique_id, {
		    	                    fade: true,
		    	                    speed: 'slow'
		    	                });
		    	            }, 5000);
        			}

        },

    	//알림 여러개 생성가능 
    initIntro: function (titleParam, textParam) {
	        var unique_id = $.gritter.add({
	            // (string | mandatory) the heading of the notification
	            title: titleParam,
	            // (string | mandatory) the text inside the notification
	            text: textParam,
	            // (string | optional) the image to display on the left
	            image: './assets/img/warning_sign.png',
	            // (bool | optional) if you want it to fade out on its own or just sit there
	            sticky: true,
	            // (int | optional) the time you want it to be alive for before fading out
	            time: '',
	            // (string | optional) the class name you want to apply to that specific message
	            class_name: 'my-sticky-class'
	            });
		            // You can have it return a unique id, this can be used to manually remove it later using
		            setTimeout(function () {
		                $.gritter.remove(unique_id, {
		                    fade: true,
		                    speed: 'slow'
		                });
		            }, 5000);
	
	}

    };

}();