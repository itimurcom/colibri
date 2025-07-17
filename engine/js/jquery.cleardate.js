$(document).ready(function()
	{
	var old_fn = $.datepicker._updateDatepicker;

	$.datepicker._updateDatepicker = function(inst) {
		old_fn.call(this, inst);
	
		if ($(inst.input).datepicker('option', 'clearEnable')==true)
			{
			var clearText = $(inst.input).datepicker('option', 'clearText');
			if (clearText==null) clearText = 'Clear';
		
			var buttonPane = $(this).datepicker("widget").find(".ui-datepicker-buttonpane");
			$("<button type='button' class='ui-datepicker-clean ui-state-default ui-priority-secondary ui-corner-all'><span class='lightred'>" + clearText + "</span></button>").appendTo(buttonPane).click(function(ev)
      				{
      				$.datepicker._clearDate(inst.input);
      				}) ;
 			}
 		}
});