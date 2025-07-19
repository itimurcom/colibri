$(document).ready(function()
	{
	set_openclose();
	});


function set_openclose()
	{
	// openclose
	$.each( $('.openclose'), function () {
		if ($(this).attr('evented')=='1') return;
		
		var sw 		= $(this).find('.switch')[0];
		var state 	= $(sw).attr('rel-state');

		var btn 	= $(sw).attr('rel').split(',');
		var close_text 	= btn[0];
		var close_class = btn[1];
		var open_text 	= btn[2];
		var open_class	= btn[3];
		
		var block = $(this).find('.block')[0];

		var effect 	= $(block).attr('rel-eff');
		var duration 	= $(block).attr('rel-dur');

		if (state=='close')
			{
			$(sw).removeClass(open_class);
			$(sw).addClass(close_class);
			$(sw).html(close_text);
			} else	{
				$(sw).removeClass(close_class);
				$(sw).addClass(open_class);
				$(sw).html(open_text);
				$(block).toggle();
				}

		$(sw).click(function ()
			{
			var openclose = $(this).closest('.openclose');
			var state = $(this).attr('rel-state');
			var block = $(this).parent().find('.block');
			var less = $(openclose).find('.less');

			if (state=='open')
				{
				$(this).removeClass(open_class);
				$(this).addClass(close_class);
				$(this).html(close_text);
				$(this).attr('rel-state', 'close');
				} else	{
					const closeother = $(sw).attr('close-other');
					if (closeother!==undefined) {
						openclose_closeAll();
						}
					
					$(this).removeClass(close_class);
					$(this).addClass(open_class);
					$(this).html(open_text);
					$(this).attr('rel-state', 'open');
					}

       			if (less.length!=0)
				{	
				$(openclose).fadeOut(duration, effect, function()
					{
					$(openclose).fadeIn(duration, effect);
					$(block).toggle();
        	       			$(less).toggle();
					});
				} else	{
					$(block).toggle(duration, effect);
					}

			if ($(this).hasAttr('data-set'))
				{
				ajax_openclose(this);
				}
	       		});
		$(this).attr('evented', '1');
		});
	}

function ajax_openclose(element)
	{
	var data = $(element).attr('data-set');
	var state = $(element).attr('rel-state');
	var alerton = $(element).attr('rel-alert');
	
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data: 
			{
			op: 'openclose',
			data : data,
			value: state,
			},
		success: function(msg)
			{
			try
			{
			var obj = jQuery.parseJSON(msg);	
			if (obj['result']!=1)
				{
				alert(msg);
				} else if (alerton)
					{
					show_loader('set', 'upload');
					}
			} catch(e) {
 				console.error('ERROR: '+ msg);
				}
			},
		});

	}

function openclose_openAll(element)
	{
	const rel = (element==undefined) 
		? ''
		: '#' + $(element).attr('rel') +' '; 
	$.each($(rel + '.openclose'), function()
		{
		var sw 		= $(this) .find('.switch')[0];
		var state 	= $(sw).attr('rel-state');
		if (state=='close')
			{
			$(sw).click();
			}
		});
	}

function openclose_closeAll(element) {
	const rel = (element==undefined) 
		? ''
		: '#' + $(element).attr('rel') +' '; 
	console.log(rel);
	$.each($(rel + '.openclose'), function()
		{
		var sw 		= $(this) .find('.switch')[0];
		var state 	= $(sw).attr('rel-state');
		if (state=='open') {
			$(sw).click();
			}
		});
	}

// нужно создать два обработчика
