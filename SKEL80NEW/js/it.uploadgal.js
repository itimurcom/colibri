var upgal_delimiter = '|';

$(document).ready(function()
	{
	set_upload_gal_events();
	});


function uploaded_img_x(element)
	{
	var avatar = $(element).closest('.uploaded');
	var data = '#' + $(element).attr('rel-data');
	var gallery = '#' + $(data).attr('rel');

	$(avatar).remove();
	$(data).attr('value','');	
	var images=[];
	
	$(gallery + " span:has(img.gal_remove)").each(function ()
		{
		var imagename = $(this).attr('rel');
		images.push(imagename);
		});

	$(data).attr('value', images.join(upgal_delimiter));
	}

function set_upload_gal_events()
	{
	$.each ($('.upload_gal_btn[evented!=upgal]'), function ()
		{
		$(this).off('change').on('change', function ()
			{
			var rel = $(this).attr('rel');
			var gallery = '#gallery-' + $(this).attr('rel');
			
			var accept = $(this).attr('accept');
			var name = $(this).attr('name');
			
			var len = this.files.length;
			var i;
			var count = 0;
			
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

			myData.append('rel', rel);
			myData.append('op', 'upload_gal');
			
			$(gallery).append("<span id='uploadreload'><img src='data:image/gif;base64,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'></span>");
			$.ajax({
        	        	type: 	'POST',
				url:	'/ed_field.php',
				data: 	myData,
				contentType: false,
				processData: false,
			beforeSend: function(msg)
				{
				show_loader('Uploading', 'upload', '1');
				$('#abort').click(function()
					{
					$('.modal_message span').html('Canceling');
					ajax.abort();
					window.location.reload();
					});
				},
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
//					alert(msg);
					try	{
						var obj = jQuery.parseJSON(msg);				
						if (obj['result']==1)
							{
							$('.modal_message span').html('Done');
							// добавим файл в списко фалов, которые уже загружены
							var images=[];
							var value = $('#' + rel).attr('value');
							if (value!='')
								{
								images = value.split(upgal_delimiter);
								}
							var income = obj['images'].split(upgal_delimiter);
							
							income.forEach(function(entry) {
								images.push(entry);
								});
														
							$('#' + rel).val(images.join(upgal_delimiter));

							// добавим изображение в галлерею поля
							$('#uploadreload').remove();
							$(gallery).append(obj['value']);
							set_fancybox_data();
							hide_loader();							
							} else
								{
								console.log(msg);		
								}
						// сброс скыртого поля ввода

						}
					catch(e) {
						console.error('ERROR: '+ msg);
						}
					},
				});
			});
		$(this).attr('evented','upgal');
		});
	}