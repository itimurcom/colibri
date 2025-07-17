// ================ CRC ================
// version: 1.27.03
// hash: 4f65430da8cd5dd6d8ac08b123e6ef9a547aa1b9de366fac9a70fba37d8778b2
// date: 29 March 2021 17:26
// ================ CRC ================
var counter_index;
var counter_reset=5;

$(document).ready(function()
	{
	if ( (typeof update_all_counters_interval)=='undefined') return;
	counter_index = 1;
	// автообновление поля сообщений
	window.setTimeout(function()
		{
		window.setInterval(function()
			{
			update_counters_set();
			}, update_all_counters_interval);
		}, 100);
		
	if (debug_counter==1) console.log('optimal counters set');
	});

function update_counters_set()
	{
	if (typeof(ajax_counter_flag) !== 'undefined') return;
		
	var myData = new FormData();
	var count = 0;

	$.each( $('.counter[rel]'), function ()	{
		myData.append('var[]', $(this).attr('rel'));
		count++;
		});

	counter_index++;
	if (counter_index==counter_reset) counter_index=1;
	
	myData.append('index', counter_index);
	myData.append('op', 'counters_event');

	if (count>0)		
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data: myData,
		processData: false,
		contentType: false,
		
		beforeSend: function ()
			{
			ajax_counter_flag = 1;	
			},

		success: function(msg)
			{
			try	{
				var json = $.parseJSON(msg);
				}
				catch(e) {
					console.error('ERROR JSON: '+ msg);
					}

				if (json.result==1)
					{
					$.each( json.counters, function( index, value )
						{
						var o_value = $('#'+value.element).attr('value');
						var n_value = $(value.value).attr('value');
						
						if (o_value != n_value)
							{
							var new_element = '#' + $(value.value).attr('id');
							
							$('#'+value.element).replaceWith($(value.value));
							
							if (o_value < n_value)
								{
								$(new_element).addClass('new');
								setTimeout(function() { $(new_element).removeClass('new'); }, 1000);
								}
							}
						});						
					}
			},

		complete : function ()
			{
			delete ajax_counter_flag;
			},

		error:	function (msg) {
//			show_loader('can not update counter', 'error');
			}
		});	
	}