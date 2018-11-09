$(document).ready(function(){ 

	jQuery.fn.center = function () {
	    this.css("position","absolute");
	    this.css("top", Math.max(0, (($(window).height() - this.outerHeight()) / 4) + 
	                                                $(window).scrollTop()) + "px");
	    this.css("left", Math.max(0, (($(window).width() - this.outerWidth()) / 2) + 
	                                                $(window).scrollLeft()) + "px");
	    return this;
	};

	$(".shadowboxContent").css("display", "none");

	jQuery('<div id="overlay"></div>').appendTo('body');
	$("#overlay").click(function() {
		hideLightbox();
	});
	$(".popupLink").click(function() {
		var str = $(this).attr("box");
		showLightbox($("#" + str));
	});
});


function showLightbox(lightbox) {
//	$("#overlay").css('display', 'block');

	$(lightbox).center();
	$(lightbox).fadeIn();
	$("#overlay").fadeIn();

	$("#overlay").css('position','fixed');
	$(lightbox).css('position', 'fixed');
	$(lightbox).css('max-height', '500px');
	$(lightbox).css('overflow', 'auto');

//	$(lightbox).css('display', 'block');
}

function hideLightbox()
{
	$("#overlay").fadeOut();
	$(".shadowboxContent").fadeOut();
}
