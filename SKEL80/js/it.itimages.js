// ================ CRC ================
// version: 1.15.01
// hash: 1f1564fa5e2a726029d5ef952a16589d18bf2a49443126d9353d2963ce083625
// date: 16 September 2018 17:02
// ================ CRC ================
function itimages_state(element)
	{
	var data = $(element).attr('rel');
	if ($(element).attr('evented')!=1)

	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'itimagesstate',
			data: data,
			},

		beforeSend: function()
			{
			$(element).attr('evented',1);
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
						$(element).replaceWith($(obj['value']));
						}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
			
			},
		complete : function ()
			{
			set_editor_events();
			refine_events();
			$(element).removeAttr('evented');
			},
		error:	function (request, error) {
			}
		});		
	}
	
function itimages_reload(element)
	{
	var data = $(element).attr('rel');
	if ($(element).attr('evented')!=1)

	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'itimagesreload',
			data: data,
			},

		beforeSend: function()
			{
			$(element).attr('evented',1);
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
						$(element).replaceWith($(obj['value']));
						}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
			
			},
		complete : function ()
			{
			set_editor_events();
			refine_events();
			$(element).removeAttr('evented');
			},
		error:	function (request, error) {
			}
		});		
	}