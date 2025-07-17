// ================ CRC ================
// version: 1.15.03
// hash: f4b9318d3949582f4477a92cb58d215491952679be8512814700cfb8d9ba0fef
// date: 10 March 2021  9:27
// ================ CRC ================
$(document).ready(function()
	{
	set_modal_events();

	window.setInterval(function()
		{
		$.each( $('.reveal-modal'), function ()
			{
			if ($(this).css('opacity')==1)
				{
				flow_modal(this);
				}
			});
		}, modal_resize_interval);

	});

function set_modal_events()
	{
	$.each( $('*[data-reveal-id][evented!=1]'), function ()
		{
		if (debug_modal==1) console.log('setting modal events...');
		$(this).on('click', function(e)
			{
			e.preventDefault();
			open_modal(this);
			});
		$(this).attr('evented','1');
		});

	}

function flow_modal(modalWindow)
	{
       	var 	$modal = $(modalWindow);
	if ($modal.hasAttr('flow')) return;
	
	$modal.attr('flow','1');
	var	height 		= $modal.outerHeight();
		width 		= $modal.outerWidth();
		topCorner 	= Math.ceil(($(window).height() - height) / 2),
		leftCorner 	= Math.ceil(($(window).width() - width) / 2),
		position	= $modal.position()

	if 	( 
		($modal.css('opacity')==1) 
		&& (height>32) 
		&& ((parseInt(position.top - topCorner)!=0) || (parseInt(position.left - leftCorner)!=0))
		)
		{
		if (debug_modal==1) console.log('flow modal' + $(modalWindow).attr('id'));
		$modal.css({
			'top': topCorner,
			'left': leftCorner,
			});
		}
	$modal.removeAttr('flow');
	}


function open_modal(element)
	{
	var modalWindow = '#' + $(element).attr('data-reveal-id');
//	alert(modalWindow);
        var 	defaults =
		{  
		animation: 'fade', 		//fade, fadeAndPop, fadeAndUp,none
		animationspeed: 300, 		//how fast animtions are
		closeonbackgroundclick: true,	//if you click background will modal close?
		dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
	    	}; 

//	var	options = $(modalWindow).data();

        var 	options = $.extend({}, defaults, options); 

       	var 	$modal = $(modalWindow),
		topOffset	= -$modal.outerHeight()+16,
		bottomOffset 	= $(window).height() + $modal.outerHeight(),
		topCorner 	= ($(window).height() - $modal.outerHeight()) / 2 ,
		leftCorner 	= ($(window).width() - $modal.outerWidth()) / 2;
		

	$modal.after("<div class='reveal-modal-bg'>&nbsp;</div>");
	$('.reveal-modal-bg').css({'visibility':'visible', 'z-index': ($modal.css('z-index')-1)}).animate({'opacity' : 1}, options.animationspeed/2);
	$('.' + options.dismissmodalclass).click(function () 
		{
		close_modal(modalWindow);
		});

	$('body').keyup(function(e)
		{
        	if(e.which===27)
			{
			close_modal(modalWindow);
			$('body').off('keyup');
			}
		});

	$('.reveal-modal-bg').click(function()
		{
		close_all_modals();
//		close_modal(modalWindow);
		$('body').off('keyup');
		});

	switch(options.animation)
		{
		case 'fadeAndPop' : {
			$modal.css({
				'top': topOffset,
				'opacity' : 0,
				'visibility' : 'visible', 
				'left' : leftCorner,
				});

			$modal.delay(options.animationspeed/2).animate(
				{
				'top': topCorner,
				'left': leftCorner,
				'opacity' : 1,
				}, options.animationspeed);
			break;
			}

		case 'fade' : {
			$modal.css({
				'opacity' : 0, 
				'visibility' : 'visible', 
				'top': topCorner, 
				'left' : leftCorner,
				});
			$modal.delay(options.animationspeed/2).animate(
				{
				'opacity' : 1,
				}, options.animationspeed);					
			break;
			}

		case 'fadeAndUp' : {
			$modal.css({
				'top': bottomOffset,
				'opacity' : 0,
				'visibility' : 'visible',
				'left' : leftCorner,
				});
			$modal.delay(options.animationspeed/2).animate(
				{
				'top': topCorner,
				'left': leftCorner,
				'opacity' : 1,
				}, options.animationspeed);					
			break;
			}

	
		} // switch;
	}

function close_all_modals()
	{
	$('.reveal-modal').each(function(){
		close_modal($(this));
		});
	}

function close_modal(modalWindow)
	{
        var 	defaults =
		{  
		animation: 'fade', 		//fade, fadeAndPop, fadeAndUp,none
		animationspeed: 400, 		//how fast animtions are
		closeonbackgroundclick: true,	//if you click background will modal close?
		dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
	    	}; 
    	
        var 	options = $.extend({}, defaults, options); 


       	var 	$modal = $(modalWindow),
		topOffset	= -$modal.outerHeight()+16,
		bottomOffset 	= $(window).height() + $modal.outerHeight(),
		topCorner 	= ($(window).height() - $modal.outerHeight()) / 2 ,
		leftCorner 	= ($(window).width() - $modal.outerWidth()) / 2;

	$modal.animate({
		'opacity' : 0,
		}, options.animationspeed/2, function()
			{
			$modal.css({
				'visibility' : 'hidden',
				});
			$('.reveal-modal-bg').animate({'opacity':0},options.animationspeed/2, 'swing', function()
				{
				$('.reveal-modal-bg').remove();
				$('.' + options.dismissmodalclass).off('click');
				});
			});


	}

