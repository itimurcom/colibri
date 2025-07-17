var ckeditor_ver=4;

$(document).ready(function ()
	{
	window.initial.push(
		"set_spinner_events",
		);

// 	$(".site_container").animate({opacity :'1'}, 600);
	refine_events();
	console.log('ready');
}); // ready

function calc_order(element, action)
	{
	var form = $('#' + element);
	var data = $('#' + element + '-data');
	
	$(data).val('');
	$(form).attr('action', action);
	$(form).submit();
	}

function reload_ajax_enter(element)
	{
	console.log('reload ajax enter');
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			op: 'ajaxenter',
			reload: 1,
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					console.log('replace ajax login');
					$(element).replaceWith($(obj['form']));	
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

function go_cabinet(url)
	{
	var attr = $('input[name]').attr('id');
	if (typeof attr !== 'undefined' && attr !== false)
		{
		console.log('cabinet');
//		window.location.href = url;			
		}
	}



function onoff_land(element)
	{
	var rel = $(element).attr('rel');
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			rel: rel,
			op: '_lang',
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1) {
					$(element).toggleClass('bg_blue');	
					$(element).toggleClass('bg_gray');
					$(".lang_div a:contains('" + rel + "')").toggle();
					// if ($(element).hasClass('selected'))
					// 	{
					// 	$(element).removeClass('selected');
					// 	} else	{
					// 		$(element).addClass('selected');
					// 		}
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
