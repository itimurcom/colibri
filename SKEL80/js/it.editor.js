// ================ CRC ================
// version: 1.15.08
// hash: d3645a799d44075ed2b4b403d9292a46a239f2f1d697e9a30111a78d751d8954
// date: 29 March 2021  0:44
// ================ CRC ================
var capchasitekey;
$(document).ready(function()
	{
	$('a[href="#"]').click(function(){
		e.preventDefault();
		return false;
		})
	set_editor_events();
	$('body').on('input',"div[contenteditable=true]", function(e)
		{
		SaveFocus(this);
		});
		
	$('body').on('click',"div[contenteditable=true]", function(e)
		{
		SaveFocus(this);
		});
		
	$('body').on('keyup',"div[contenteditable=true]", function(e)
		{
		SaveFocus(this);
		});

	$('body').on('paste',"div[contenteditable=true]:not(.ed_text)", function(e)
		{
		e.preventDefault();

		// get text representation of clipboard
    		var text = strip_tags((e.originalEvent || e).clipboardData.getData('text'), 'b br p');

    		// insert text manually
    		document.execCommand("insertHTML", false, text);
    		});

	});
	

function getFormObj(element) {
    var formObj = {};
    var inputs = $(element).serializeArray();
    $.each(inputs, function (i, input) {
        formObj[input.name] = input.value;
    });
    return formObj;
}
     
function editor_edstate(element)
	{
	var data = $(element).attr('rel');
	
	if ($(element).attr('evented')!=1)
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'edstate',
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
	
function editor_edreload(element)
	{
	var data = $(element).attr('rel');
	if ($(element).attr('evented')!=1)
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'edreload',
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

function set_editor_events()
	{
	if (typeof(CKEDITOR) === "undefined" ) return;

//	console.log('setting editor events');
	// сбросим автоподклюение редактора, установим в нужном месте
	CKEDITOR.disableAutoInline = true;
	$( '.ed_text[contenteditable=true][evented!=1]' ).each( function() 
		{
		var editorID = $(this).attr("id");
		var instance = CKEDITOR.instances[editorID];
		if (instance) instance.destroy(true);		
		CKEDITOR.inline( this, { customConfig: '/engine/js/ckeditor/config.js?' + `f${(~~(Math.random()*1e8)).toString(16)}` });

		$(this).attr('evented',1);
		} );

	// установим обработку для текстовых полей редактирования
	$('body').on('blur',".ed_text[contenteditable=true]", function(e)
		{
		var $editor = $(this);
		var value = $editor.html();
		var name = $editor.attr('id');

		var rel = $editor.attr('rel-data');
		if (CKEDITOR.instances[ name ].checkDirty())
			{
			CKEDITOR.instances[ name ].resetDirty();
			$.ajax	({
				type: 'POST',
				url: '/ed_field.php',
				data:
					{
					op: 'ed_text',
					data: rel,
					value: value
					},
				success: function( msg)
					{
					var obj = jQuery.parseJSON(msg);		
					if (obj === null)
						{
						alert(msg);
						show_loader('Not Saved', 'error');	
						} else 
					if (obj['result']==1)
						{
						show_loader('Saved');
						} else 	{
							alert(msg);
							show_loader('Not Logged', 'error');	
							}
					},
				error:	function (msg) {
					show_loader('Error', 'error');	
					}
				});
			};
	    	});
	}
