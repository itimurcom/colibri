$(document).ready(function() {
	// $(document.body).on('touchmove', onGlobalScroll);
	window.onscroll = onGlobalScroll;
	$('#goup').click(function()	{
		$('html, body, #wrapper').animate({scrollTop : 0}, 800);
		return false;
		});
	});

function ckeditor_scroll() {
	if (typeof(CKEDITOR) !== "undefined" )
		CKEDITOR.document.getWindow().fire('scroll');	
	}

function onGlobalScroll(event) {
	if($(window).scrollTop() + $(window).height() > $(document).height() / 2) {
		$('#goup').fadeIn('slow');
			} else 	{
			$('#goup').fadeOut("slow");
			}
	ckeditor_scroll();
	}
