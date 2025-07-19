$(document).ready(function()
	{
	// ���� ���������
	$('.set .onoff').click(function () {
		set_onoff(this);
		});
	});



function set_onoff(element)
	{
	clk();
	var data = $(element).attr('rel');
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'onoff',
			data: data,
			},
		success: function( msg)
			{
			var obj = jQuery.parseJSON(msg);		
			if (obj === null)
				{
				show_loader('error', 'error');	
				} else 
			if (obj['result']==1)
				{
				show_loader('ok');
				if (obj['value']==1)
					{
					$(element).removeClass('off');
					$(element).addClass('on');
					} else 	{
						$(element).removeClass('on');
						$(element).addClass('off');
						}
				if ($(element).hasAttr('rel-ajax'))
					{
					eval($(element).attr('rel-ajax'));
					}
				} else 	{
					show_loader('not send', 'error');	
					}
			},
		error:	function (msg) {
			show_loader('not send', 'error');
			}
		});
	}

function is_on(data)
	{
	var id = '#set-' + data.replace('_','-').toLowerCase();
	return $(id).hasClass('on');
	}
