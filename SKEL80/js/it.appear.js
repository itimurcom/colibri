// ================ CRC ================
// version: 1.15.02
// hash: 299a4f9361fc05ba5561d9aef9a7cc2900ab3c08ef182d14b148d1e32a5b148a
// date: 24 August 2019 16:44
// ================ CRC ================
// onScreen jQuery plugin v0.2.1
// (c) 2011-2013 Ben Pickles
//
// http://benpickles.github.io/onScreen
//
// Released under MIT license.
;(function($) {
  $.expr[":"].itOnScreen = function(elem) {
    var $window = $(window)
    var viewport_top = $window.scrollTop()
    var viewport_height = $window.height()
    var viewport_bottom = viewport_top + viewport_height
    var $elem = $(elem)
    var top = $elem.offset().top
    var height = $elem.height()
    var bottom = top + height

    return (top >= viewport_top && top < viewport_bottom) ||
           (bottom > viewport_top && bottom <= viewport_bottom) ||
           (height > viewport_height && top <= viewport_top && bottom >= viewport_bottom)
  }
})(jQuery);

var appear_interval = 1000;

(function( $ ) {
	$.fn.itApear = function(selector, callback)
		{
		setInterval(function()
			{
			$(selector).filter(":itOnScreen").each(function()
				{
				callback(this);
				});
  			}, appear_interval);
 		};
 })(jQuery)