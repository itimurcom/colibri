// ================ CRC ================
// version: 1.15.04
// hash: aa9bd15fef1706b6bdc8a740dbd6097b45870b09af02fd380288ddf55493fe0e
// date: 01 August 2020 17:27
// ================ CRC ================
$(document).ready(function()
	{
	window.setInterval(function()
		{
		clear_new_flag();
		}, clear_new_interval);
	});

function clear_new_flag()
	{
	$('.new').each(function()
		{
		if (debug_new_console==1) console.log('clearing new...')
		clear_new_animated(this);
		});
	}
	
function clear_new_animated(element)
	{
	$(element).stop().animate({'background-color':'rgba(0,255,255,.01)'}, 2000, function()
		{
		$(element).removeClass('new');
		$(element).removeAttr('style');
		});
	}
	
function markviewwed(element)
	{
	var data = $(element).attr('rel-data');
	console.log('Viewed');	
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			data: data,
			},

		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);
				if (obj === null)
					{
					} else 
					if (obj['result']==1)
						{
						show_loader('ok','ok');
						$(element).addClass("new");
						$(element).removeClass("adminnew");
						$(element).attr('onclick', 'markNOTviewwed(this);');
						} else {
							console.log(obj['result']);
							}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
		
			},
		complete : function ()
			{
			},
		error:	function (request, error) {
			}
		});		
	}

function markNOTviewwed(element)
	{
	var data = $(element).attr('rel-data');
	console.log('unViewed');
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			data: data,
			op2:	'NOT',
			},

		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);
				if (obj === null)
					{
					} else 
					if (obj['result']==1)
						{
						show_loader('ok','ok');
						$(element).removeClass("new");
						$(element).addClass("adminnew");
						$(element).attr('onclick', 'markviewwed(this);');						
//						$(element).removeAttr("onclick");
						} else {
							console.log(obj['result']);
							}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
		
			},
		complete : function ()
			{
			},
		error:	function (request, error) {
			}
		});		
	}

function toggle_markview(element)
	{
	var data = $(element).attr('rel-data');
	var container = $(element).parent('.container');
	
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			data: data,
			},

		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);
				if (obj === null)
					{
					} else 
					if (obj['result']==1)
						{
// 						show_loader('ok','ok');
						if (obj['value']=='new')
							{
							$(container).removeClass("new");
							$(container).addClass("adminnew");
							} else	{
								$(container).removeClass("adminnew");
//								$(container).addClass("new");
								}
						} else {
							console.log(obj['result']);
							}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
		
			},
		complete : function ()
			{
			},
		error:	function (request, error) {
			}
		});		
	}
