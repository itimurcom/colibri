function f2_edstate(element)
	{
	var data = $(element).attr('rel');
	
	if ($(element).attr('evented')!=1)
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'f2_edstate',
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
						$(element).replaceWith(obj['value']);
						refine_events();
						}
				}
			catch(e) {
				console.error('ERROR: '+ msg);
				}
			finally	{
//			set_autogrow_events();
			
			}
			},
		complete : function ()
			{
			$(element).removeAttr('evented');
			refine_events();
			},
		error:	function (request, error) {
			}
		});
	}
	
function f2_edreload(element)
	{
	var data = $(element).attr('rel');
	if ($(element).attr('evented')!=1)
	$.ajax({
		type: 'POST',
		url: '/ed_field.php',
		data:
			{
			op: 'f2_edreload',
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
						set_editor_events();
						set_autogrow_events();
						refine_events();
						}
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					}
			
			},
		complete : function ()
			{
			$(element).removeAttr('evented');
			refine_events();			
			},
		error:	function (request, error) {
			}
		});		
	}
	
function f2_reset(element)
	{
	$('#' + element + ' input[type!=hidden]:not([readonly])').val('');
	$('#' + element + ' select:not([readonly])').val('');
	$('#' + element + ' textarea:not([readonly])').val('');	
	$('#' + element + ' .f2_row').removeClass('focus');
	$('#' + element + ' .f2_row input.red').removeClass('red');
	$('#' + element).ScrollTo({duration:800, callback:function(){}});
	}

function f2_email_checker(element)
	{
	var value = $(element).val();
	var form = $(element).parents('form:first');
	console.log('cheking: ' + value);
//	var submit = $(form).find('.submit');
	
	if (!validEmail(value))
		{
		$(element).addClass('red');
//		$(submit).css({
//			'pointer-events':	'none',
//			'opacity'	:	'.2',
//			});
			
//		$(submit).attr('email', '');
		$($('#' + $(element).attr('id'))).focus();
		} else	{
//			if (!$(submit).hasAttr('phone'))
//				{
				$(element).removeClass('red');
//				$(submit).removeAttr( 'style' );
//				$(submit).removeAttr('email');			
//				}
			}
	}
	
function f2_phone_checker(element)
	{
	var value = $(element).val();
	var form = $(element).parents('form:first');
	var submit = $(form).find('.submit');
	
	if (!validPhone(value))
		{
		$(element).addClass('red');
//		$(submit).css({
//			'pointer-events':	'none',
//			'opacity'	:	'.2',
//			});
//		$(submit).attr('phone', '');
		$($('#' + $(element).attr('id'))).focus();
		} else	{
//			if (!$(submit).hasAttr('email'))
//				{
				$(element).removeClass('red');
//				$(submit).removeAttr( 'style' );
//				$(submit).removeAttr('phone');			
//				}
			}
	}
	

function render_f2_captcha(element)
	{
// 	console.log('recaptcha' + $(element).attr('id'));
	grecaptcha.render(element, {'sitekey' : capchasitekey });
	}

function email_checker(element)
	{
	var value = $(element).val();
	var form = $(element).parents('form:first');
	var submit = $(form).find('.submit');
	
	if (!validEmail(value))
		{
		$(element).addClass('error');
		$(submit).css({
			'pointer-events':	'none',
			'opacity'	:	'.2',
			});
			
		$(submit).attr('email', '');
		$($('#' + $(element).attr('id'))).focus();
		} else	{
			if (!$(submit).hasAttr('phone'))
				{
				$(element).removeClass('error');
				$(submit).removeAttr( 'style' );
				$(submit).removeAttr('email');			
				}
			}
	}
	
function phone_checker(element)
	{
	var value = $(element).val();
	var form = $(element).parents('form:first');
	var submit = $(form).find('.submit');
	
	if (!validPhone(value))
		{
		$(element).addClass('error');
		$(submit).css({
			'pointer-events':	'none',
			'opacity'	:	'.2',
			});
		$(submit).attr('phone', '');
		$($('#' + $(element).attr('id'))).focus();
		} else	{
			if (!$(submit).hasAttr('email'))
				{
				$(element).removeClass('error');
				$(submit).removeAttr( 'style' );
				$(submit).removeAttr('phone');			
				}
			}
	}
	
function validEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
};

function validPhone(phone) {
    var re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
    return re.test(phone);
};

function update_f2_input(name, value)
	{
	var id = $('[name=' + name + ']').attr('id');

	$('#' + id).val(value);
	$('#' + id).attr('readonly', true);
	$('#' + id).find('option:not([selected])').attr('disabled', true);
	$('#' + id).removeClass('red');
	$('#' + id).addClass('green');																		
	$('#container-' + id).removeClass('focus');
	}

function set_recV3_events()
	{
	$('form[recv3]').each(function(){
		// console.log('v3: ', $(this).attr('id'));
 		recV3_submit(this);
		});
	setTimeout(function(){
		$('.grecaptcha-badge').animate({'opacity' : 0}, 600, function() {
			$('.grecaptcha-badge').css({'visibility' : 'hidden'});
			});
		}, 2000);
	}

function recV3_submit(element)
	{
	// const form = '#' + $(element).attr('id');
	const action = $(element).attr('action');
	$(element).submit(function(event){
		// console.log('submit');
		event.preventDefault();
	        grecaptcha.ready(function() {
	            	grecaptcha.execute(capchasitekey, {action:action}).then(function(token) {
			                $(element).append('<input type="hidden" name="v3resp" value="' + token + '">');
			                $(element).unbind('submit').submit();
	            		});;
        		});
		});
	$(element).removeAttr('recv3');		
	}

function ajax_submit(element, afterCallback)
	{
	console.log('ajaxsubmit:' + element);
	$.ajax	({
		type: 'POST',
		url: '/ed_field.php',
		data: getFormObj(element),

		beforeSend: function()
			{
			},

		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);
				if (obj === null)
					{
					} else
						{ 
						if (obj['result']==1)
							{
							if (obj['show']!=false)
								{
								show_loader('ok');
								}
							if (obj['type']=='ajax')
								{
								eval(obj['value']);
								}
							}
						}
						
				}
				catch(e) {
					console.error('ERROR ajax_submit: '+ msg);
					}
			
			},
		complete : function ()
			{
			set_editor_events();
			refine_events();
			if (typeof afterCallback === "function")
				afterCallback();
			},
		error:	function (request, error) {
			}
		});		
	}