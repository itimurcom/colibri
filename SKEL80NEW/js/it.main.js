var clear_new_interval 			= 5000;

var automate_dubug 			= 0;
var debug_counter 			= 0;
var debug_modal 			= 0;
var debug_new_console 		= 0;
var debug_chat 				= 0;
var debug_events			= 0;

window.initial = [ 
	"reload_lazy_events",
	"set_editor_events",
	"set_openclose",
	"set_modal_events",	
	"set_more_feed",			
	"set_fancybox_data",
	"set_comments_events",
	"set_uploads_events",
	"set_upload_gal_events",
	"set_autosize_events",
	"set_autogrow_events",
	'set_recV3_events',
	];


function refine_events()
	{
	window.initial.forEach(function(item, i, arr)
		{
		if (eval("typeof " + item) === "function")
			{
			if (debug_events) console.log(item + ': loaded');
			eval (item + "();");
			}
		});
	}

function SaveFocus(div)
	{
	var selectedFocus = document.getSelection();
	var savedfocus = [ selectedFocus.focusNode, selectedFocus.focusOffset ];			
	$(div).data('skeletonfocus', savedfocus);
//	console.log(' >> ' + selectedFocus.focusOffset);
	}
	
function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);
            
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}