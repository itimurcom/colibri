function clearlastseen()
	{
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data : {
			op: 'clearlastseen',
			},

		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				
				if (obj['result']==1)
					{
					var time = 600;
					$('#lastseen').fadeOut(time);
					$('.row.lastseen').fadeOut({duration: time, complete: function(){
						$('.row.lastseen').remove();
						}});
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

function add_whishlist(element)
	{
	var data = $(element).attr('data');
	var rel = $(element).attr('rel');

	$.ajax	({
		type: 'POST',		url: '/ed_field.php',
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
// 						console.log(obj['value']);
						$('[rel=' + rel + ']').toggleClass('on');
						$('#clearwishlist').remove();						
						reload_wishlist();
						}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
			
			},
		complete: function() {
			},
		error:	function (request, error) {
			}
		});		

	}

function reload_wishlist()
	{
	console.log('reload.wish');
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'wishlist',
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
 						$('#wishlist').html(obj['value']);
						refine_events();
						}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
			
			},
		error:	function (request, error) {
			}
		});		
	
	}

function clearwhish()
	{
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'clearwishlist',
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
						$('#wishlist').animate({opacity: 0}, 600, function(){ $('#wishlist').html(''); $('#wishlist').css('opacity', '1');});
						$('.wish.on').removeClass('on');
						}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
			
			},
		error:	function (request, error) {
			}
		});		
		
	}