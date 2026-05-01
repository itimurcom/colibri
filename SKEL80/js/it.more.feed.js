$(document).ready(function()
	{
	set_more_feed();
/*	$().itApear('.more_feed[evented=1][appear]', function(element)
		{
		$(element).trigger('click');				
		});	
*/

	setInterval(function() {
		$('.more_feed[evented=1][appear]').filter(':itOnScreen').each(function()
			{
			$(this).trigger('click');
			});
		}, 1000);
	});

function disableScrolling(wrapper)
	{
	var element = document.getElementById(wrapper);
	if (element!=null)
		{
		var top=element.scrollTop;
		element.onscroll=function(){element.scrollTop = top;};
		}
	}

function enableScrolling(wrapper)
	{
	var element = document.getElementById(wrapper);
	if (element!=null)
		{
		element.onscroll=function(){};
		}
	}
	
function set_more_feed()
	{
	$('.more_feed[evented!=1]').each(function()
		{
		$(this).css('cursor','pointer');
		$(this).click(function(e)
			{
			more_click(this);
			});
						
		var logo = $(this).find('.more_logo');
		var text = $(this).find('.more_text');
		
		if (!$(logo).hasAttr('norotate'))
		$(this).hover(function ()
			{
			$(logo).rotate({ count:99999, forceJS:true });
			}, function ()
				{
				$(logo).stop();
				});

		$(this).attr('evented','1');
		
		if ($(this).hasAttr('async'))
			{
			more_click(this);
			$(this).removeAttr('async');
			}
		});
	};


function more_click(element)
	{
	var feed = $(element);
	var new_id = 'new-'+ $(element).attr('id');	
	var rel = $(element).attr('feed-rel');
	var scroll = $(element).offset().top;

	var auto = $(element).hasAttr('auto');

//	ga('send', 'pageview');
	$(element).find('.more_text').text('RELOADING...').animate({'opaciti':0},300);
	$(element).find('.more_logo').rotate({ count:99999, forceJS:true });

	
	if ($(element).attr('clicked')!=='1')
	$.ajax({
		type: 'POST',
		url: '/more.php',
		data: 
			{
                        data : rel,
			},
		beforeSend: function()
			{
			$(element).attr('clicked', '1');
			disableScrolling('wrapper');
			},
		success: function(msg)
			{
			try	{
				var obj = $.parseJSON(msg);				

				if (obj['result']==1)
					{
					$(feed).fadeOut(300, function()
						{
						var div = $("<div class='morenew' id='" + new_id + (auto ? ' auto' : '') +"'>"+obj['value']+"</div>").css('opacity', '0');
						$(feed).replaceWith(div);
						refine_events();

						fadeIn(new_id, 800, function ()
							{
							if (!$(element).hasAttr('scroll') || $(element).hasAttr('appear'))
								{
								$('#' + new_id).removeAttr('id');
								enableScrolling('wrapper');
								refine_events();
								} else 	{
									enableScrolling('wrapper');
									$('#'+new_id).ScrollTo(
										{
										duration:800, 
										callback: function()
											{
											var el = document.getElementById(new_id);
											el.scrollIntoView(true);
												
											$('#' + new_id).replaceWith(obj['value']);
											refine_events();
											}
										});									
									}
							});
						});
					} else alert(msg);
				}
				catch(e) {
					console.error('ERROR: '+ msg);
					enableScrolling('wrapper');
					}
				
			},
		error:	function (jqXHR, textStatus, errorThrown)
			{
			alert (jqXHR.responseText);
			enableScrolling('wrapper');
			}

		});
	}


function fadeIn(element, time, callback) {
var el = document.getElementById(element);	
  el.style.opacity = 0;

  var last = +new Date();
  var tick = function() {
    el.style.opacity = +el.style.opacity + (new Date() - last) / time;
    last = +new Date();

    if (+el.style.opacity < 1) {
      (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
    } else if (typeof callback === 'function' )
    	{
	callback(element);
    	}
  };

  tick();
}

function fadeOut(element, time, callback) {
var el = document.getElementById(element);	
  el.style.opacity = 0;

  var last = +new Date();
  var tick = function() {
    el.style.opacity = +el.style.opacity - (new Date() - last) / time;
    last = +new Date();

    if (+el.style.opacity > 0) {
      (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
    } else if (typeof callback === 'function' )
    	{
	callback(element);
    	}
  };

  tick();
}