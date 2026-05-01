$(document).ready(function()
	{
	// кнопка "вверх"
	$(window).scroll(function()
		{
		if ($(this).scrollTop() > 100)
			{
			$('#goup').fadeIn('slow');
			} else 	{
				$('#goup').fadeOut("slow");
				}
		ckeditor_scroll();			
		});

	if ($('#wrapper').length > 0)
	$("#wrapper").scroll(function()
		{
		$(window).trigger('resize');
		if ($(this).scrollTop() > 100)
			{
			$('#goup').fadeIn('slow');
			} else 	{
				$('#goup').fadeOut("slow");
				}
		ckeditor_scroll();
		});


	$('#goup').click(function()
		{
		$('html, body, #wrapper').animate({scrollTop : 0},800);
		return false;
		});
	});

function ckeditor_scroll()
	{
	if (typeof(CKEDITOR) !== "undefined" )
		CKEDITOR.document.getWindow().fire('scroll');	
	}