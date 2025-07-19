// ================ CRC ================
// version: 1.15.01
// hash: c2db14c7898b91174b9b51da484d57800213b77cfcfeb270a4acf056a4dda42d
// date: 16 September 2018 17:02
// ================ CRC ================
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