// ================ CRC ================
// version: 1.15.01
// hash: 341f2da5c7095ca3d55cd599f2372d615c91f78b9d305eb0fc68b8bfede814a5
// date: 16 September 2018 17:02
// ================ CRC ================
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