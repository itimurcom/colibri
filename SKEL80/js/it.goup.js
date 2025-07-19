// ================ CRC ================
// version: 1.15.04
// hash: 63776938751035d832ae97f234073b606198bd1a874490463e4ffb0478166707
// date: 17 September 2019 17:56
// ================ CRC ================
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