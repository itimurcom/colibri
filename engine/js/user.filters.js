function select_filter(color, url)
	{
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			value: color,
			op: 'filter',
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					if (obj['url']!='')
						{
						location.reload(true);	
//						window.location.href = obj['url'];
						} else location.reload(true);
					} else alert(msg);
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
				
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			}

		});
	}
	
function select_item_color(element)
	{
	var data = $(element).attr('rel');
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			data: data,
			op: 'item_color',
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					if ($(element).hasAttr('clear'))
						{
//						location.reload();
						$(element).parents('.col_selector').find('.col_sel').each(function()
							{
							$(this).removeClass('selected');	
							})
						} else
					if ($(element).hasClass('selected'))
						{
						$(element).removeClass('selected');
						} else	{
							$(element).addClass('selected');
							}
					} else alert(msg);
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
				
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			}

		});
	}
	
function set_tag(element)
	{
	var data = $(element).attr('rel');
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			data: data,
			op: 'tag',
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					window.location.href = obj.value;
					} else alert(msg);
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
				
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			}

		});
	}
	
function kill_tag(element)
	{
	var data = $(element).attr('rel');
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			data: data,
			op: 'kill_tag',
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					window.location.href = obj.value;
					} else alert(msg);
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
				
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			}

		});
	}

function sort_price(element)
	{
	var sort = $(element).val();
	var min = $('#slider-range').slider('values', 0);
	var max = $('#slider-range').slider('values', 1);
	
// 	alert(min + " : "+ max);

	$('#range-btn').rotate({ count:99999, forceJS:true });
	
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			op: 'itemsort',
			sort: sort,
			min:min,
			max:max,
			},

		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					location.reload(true);		
					} else alert(msg);
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
				
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			}
		});
		
	}