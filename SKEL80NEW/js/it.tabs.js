function active_tab(element)
	{
	set_tab(element);

	var tabs = '#' + $(element).data('rel');
	var menu = '#menu-' + $(element).data('rel');
	var tab = '#tab-' + $(element).data('rel') + '-' +$(element).data('tab')
	var inset = tab.replace('tab-', 'inset-');

	$(tabs + ' .inset').removeClass('active');
	$(tabs + ' .inset').addClass('nomobile');
	$(inset).addClass('active');
	$(inset).removeClass('nomobile');


	$(tabs + ' .tab .container').animate({opacity:0}, 300, function ()
		{
		$('.chart').each(function()
			{
			$(this).css('opacity','0');
			});

		$(tabs + ' .tab').removeClass('active');
		$(tab).addClass('active');

		$(tab + ' .container').css("opacity","0").animate({opacity:1}, 300, function ()
			{
			$('.chart').each(function()
				{
				$(this).CanvasJSChart().render();
				if ($(this).css('opacity')==0)
					{
					$(this).animate({opacity:1},300);
					};
				});
			});

		});

	}

function set_tab(element)
	{
	var data = $(element).data('set');
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data: 
			{
			op: 'tab',
			data : data,
			},
		success: function(msg)
			{
			var obj = jQuery.parseJSON(msg);	
			if (obj['result']!=1)
				{
				alert(msg);
				}
//			$('.chart').updateSize();
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			}

		});

	}