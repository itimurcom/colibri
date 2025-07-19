$(document).ready(function()
    {
    set_fancybox_data();
    });

function getFancyItems(pic)
	{
	var items = [];
        $(pic).find('a[class=fancybox]').each(function()
		{
        	var href  = $(this).attr('href');
    	    var size  = $(this).data('size').split('x');
        	var caption  = $(this).data('caption');
			if (size[0]>0 ) {
				var width  = size[0];
				var height = size[1];
				} else {
					const img = $(this).find('img');
					var width = img.width()*2;
					var height = img.height()*2;
				}
		var item = {
			src : href,
			msrc : href,
            		    w   : width,
                		h   : height,
                	title : caption,
                		}

		$(this).attr('gal-index',items.length);
		items.push(item);
		});
	return items;
	}


function lightbox(event, pic, element)
	{
	event.preventDefault();
	var items = getFancyItems(pic);

	var sel = parseInt($(element).attr('gal-index'));
	var offset = $(element).find('img').offset();
	var width = $(element).find('img').width();
    
	var options =
		{
		index: sel,
		bgOpacity: 0.9,
		showHideOpacity: true,
		linkEl: false,
		shareEl: false,
		getThumbBoundsFn: function(index)
			{
			return {x:offset.left, y:offset.top, w:width};
    			}
		}
		
	var $pswp = document.querySelectorAll('.pswp')[0];
	var light = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
	light.init();		
	}    


function set_fancybox_data() {
    $(".fancygall").each( function()
	{
    	var $pic = $(this);
    	var items = getFancyItems($pic);
    	var $thumb;

    	// попытка создать изображения - нужно для ресайзинга
    	var image = [];
    	$.each(items, function(index, value)
		{
		image[index]     = new Image();
		image[index].src = value['src'];
		});

	$pic.find('a[class=fancybox][evenyed!=1]').each( function()
		{
		this.onclick = function(event) 
			{
			lightbox(event, $pic, this);
			}
// старая версия
//		$(this).unbind('click').click(function(event) { alert('click'); lightbox(event, $pic, this);});
		$(this).attr('evented',1);
		});
	});
    }