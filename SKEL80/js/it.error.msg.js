$(document).ready(function()
	{
	if (typeof error_msg_fadeout !== 'undefined')
		{
		$('.error_msg').fadeTo(5000, 0.01, function(){ 
			$(this).slideUp(150, function() {
			$(this).remove(); 
			}); 
			});
		}
	});