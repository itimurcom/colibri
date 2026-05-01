$(document).ready(function()
	{
	// установка фокуса
	var element = $('#focus').attr('rel');

	if (typeof(element)!=='undefined')
		{
		var color = $('#focus').attr('rel-color');
		var data = $('#focus').attr('rel-data');
		$('#'+element).addClass(color);
		if (data)
			{
			$('#'+element).val(data);
			}
		$('#'+element).focus();
		}
	});