$(document).ready(function()
	{
	set_uploads_events();
	});


function set_uploads_events()
	{
	// событие для скрытых полей выбора файла
	$.each ($('input:file[evented!=uploads]:not([multiple])'), function ()
	{
	if ($(this).hasAttr('evented')) return;

	$(this).on('change', function ()
		{
		var data = $(this).attr('rel-data');
		var value = $(this).attr('rel-value');
		var accept = $(this).attr('accept');

		var op = $(this).attr('rel-op');
		var name = $(this).attr('name');

		var myData = new FormData();
		var myFile = this.files[0];

		var count = 0;

		if(accept.indexOf(myFile.type) == -1)
			{
			alert(myFile.name + ' file format not allowed! ('+accept+')');
			} else	{
				var reader = new FileReader();
				reader.readAsDataURL(myFile);
				myData.append(name + '[]', myFile);
				count++;
				}
		if (count==0) return;

		myData.append('value', value);
		myData.append('data', data);
		myData.append('op', op);

		var ajax = $.ajax({
	      	        type: 	'POST',
			url:	'/ed_field.php',
			data: myData,
			contentType: false,
			processData: false,
			xhr: function()
				{
				console.log('in progress');
				var progressBar = $('#progressbar');
        			var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
				xhr.upload.addEventListener('progress', function(evt)
					{ // добавляем обработчик события progress (onprogress)
//					if(evt.lengthComputable)
						{ // если известно количество байт
						// высчитываем процент загруженного
						var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
						// устанавливаем значение в атрибут value тега <progress>
						// и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
						progressBar.attr('value', percentComplete);
						}
					}, false);
				return xhr;
				},
			success: function(msg) 
			        { 
				try	{
					var obj = jQuery.parseJSON(msg);	
					}
					catch (e)
						{
						alert(msg);
						}
				if (obj['result']==1)
					{
					if (obj['type']=='ajax')
						{
						$('.modal_message span').html('Done');
						eval(obj['value']);
						hide_loader();
						} else  {
							$('.modal_message span').html('Reloading');
							window.location.href=obj['value'];
							window.location.reload();
							}
					} else alert(msg);
				},
			beforeSend: function(msg)
				{
				show_loader('Uploading', 'upload', '1');
				$('#abort').click(function()
					{
					$('.modal_message span').html('Canceling');
					ajax.abort();
					window.location.reload();
					});
				}
		        });
		});
	$(this).attr('evented','uploads');
	});

	// событие для скрытых полей выбора группы файлов
	$.each ( $('input:file[evented!=uploads][multiple]'), function ()
	{
	if ($(this).hasAttr('evented')) return;

	$(this).on('change', function ()
		{
		var data = $(this).attr('rel-data');
		var value = $(this).attr('rel-value');

		var accept = $(this).attr('accept');

		var op = $(this).attr('rel-op');
		var name = $(this).attr('name');

		var myData = new FormData();
                var myFile;
		var len = this.files.length;
		var i;
		var count = 0;

		for (i=0;i<len;i++)
			{
			myFile = this.files[i];
			if(accept.indexOf(myFile.type) == -1)
				{
				alert(myFile.name + ' file format not allowed! ('+accept+')');
				} else	{
					var reader = new FileReader();
					reader.readAsDataURL(myFile);
					myData.append(name + '[]', myFile);
					count++;
					}
			}
		if (count==0) return;
		myData.append('data', data);
		myData.append('value', value);
		myData.append('op', op);


		$.ajax(
	        	{
        	        type: 	'POST',
			url:	'/ed_field.php',
			data: myData,
			contentType: false,
			processData: false,
			xhr: function()
				{		
				var progressBar = $('#progressbar');
        			var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
				xhr.upload.addEventListener('progress', function(evt)
					{ // добавляем обработчик события progress (onprogress)
//					if(evt.lengthComputable)
						{ // если известно количество байт
						// высчитываем процент загруженного
						var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
						// устанавливаем значение в атрибут value тега <progress>
						// и это же значение альтернативным текстом для браузеров, не поддерживающих <progress>
						progressBar.val(percentComplete);
						}
					}, false);
				return xhr;
				},
			success: function(msg) 
			        {
				try	{
					var obj = jQuery.parseJSON(msg);	
					}
					catch (e)
						{
						alert(msg);
						}
				if (obj['result']==1)
					{
					if (obj['type']=='ajax')
						{
						$('.modal_message span').html('Done');
						eval(obj['value']);
						hide_loader();
						} else  {
							$('.modal_message span').html('Reloading');
							window.location.href=obj['value'];
							window.location.reload();
							}
					} else alert(msg);
	                	},
			beforeSend: function(msg)
				{
				show_loader('Uploading', 'upload', '1');
				}
		        });
		});
       	$(this).attr('evented','uploads');
	});
	}
