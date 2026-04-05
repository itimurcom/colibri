// ================ CRC ================
// version: 1.15.01
// hash: b31413f1e7c1bdb79a2dfa7ef7b73755734714a5c0268ff667585992e75774c3
// date: 16 September 2018 17:02
// ================ CRC ================
function show_loader(text, color, nohide)
	{
	var progress = '';
	if (text=='Uploading')
		{
		var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
		progress = "<progress id='progressbar' value='0' max='100'></progress>" +
			"<a id='abort' href='#'>Cancel</a>";
		$(document).on('click', '.abort', function(e)
			{      
			alert('click');
		    	xhr.abort();
			});
		}
	$('body').append("<div class='modal_message "+ color +"'><span>"+text+"</span>"+progress+"</div>");
	if (typeof nohide == 'undefined')
		{
		hide_loader();
		}
	}

function hide_loader()
	{
	$('.modal_message').fadeOut(4000, function() {$('.modal_message').remove()});
	}
