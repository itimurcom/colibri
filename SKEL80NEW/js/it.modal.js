$(document).ready(function() {
	set_modal_events();

	$(window).on('resize', function() {
		var modals = document.getElementsByClassName('reveal-modal');
		for(idx=0; idx<modals.length; idx++) {
			if (modals[idx].style.opacity == 1 && modals[idx].style.visibility=='visible' ) {
				flow_modal(modals[idx]);
				}
			}
		});
	});		

function set_modal_events() {
	$.each( $('*[data-reveal-id][evented!=1]'), function () {
		if (debug_modal==1) console.log('setting modal event for ' + $(this).attr('id'));
		$(this).on('click', function(e) {
			if (debug_modal==1) console.log('click ' +$(this).attr('id'));
			e.preventDefault();
			open_modal(this);
			});
		$(this).attr('evented','1');
		});

	}

function flow_modal(modalWindow) {
	var modal = document.getElementById(modalWindow.id);

	const height 		= modal.offsetHeight;
	const width 		= modal.offsetWidth;
	const topCorner 	= Math.round((window.innerHeight - height) / 2);
	const leftCorner 	= Math.round((window.innerWidth - width) / 2);

	modal.style.top = topCorner  + 'px';
	modal.style.left = leftCorner + 'px';
	}


function open_modal(element)
	{
	const attr = $(element).attr('data-reveal-id');
	var modalWindow = (attr === undefined)
		? element
		: "#" + attr;


	if (debug_modal==1) console.log('opening modal ' + modalWindow);
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
		
	// $(document.body).append( $modal.detach() );

	$modal.after("<div class='reveal-modal-bg'>&nbsp;</div>");
	$('.reveal-modal-bg').css({'visibility':'visible', 'z-index': ($modal.css('z-index')-1)}).animate({'opacity' : 1}, options.animationspeed/2);
	$('.' + options.dismissmodalclass).click(function ()  {
		close_modal(modalWindow);
		});

	$('body').keyup(function(e) {
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