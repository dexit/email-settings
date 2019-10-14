/*
	Declare names of WP_EDITOR instance passed from PHP 
*/
var names = php_vars.names;
/*
	Add change and keyup events to both textareas related to wp_editor(s) and wp_editor(s) themselves,
	so function 'parse_value' will be called on each textarea/wp_editor keyup / change event
*/
jQuery(function(){
	/*
		Create an array of intervals and editors based on length of names(of wp_editor instances/variables) array
	*/
	var intervals = new Array(names.length);
	var editors = new Array(names.length);
	/*
		Process all instances of wp_editor
	*/
	for(var i = 0; i < names.length; i++)
	{
		/*
			Bind function 'parse_value' to textareas
		*/
		jQuery("#"+names[i] + '-editor').change(function(){
			parse_value();
		});
		jQuery("#"+names[i] + '-editor').keyup(function(){
			parse_value();
		});
		/*
			We need to use the interval to be able to wait until wp_editor is initialized
		*/
		intervals[i] = setInterval(function(i_var){

			/*
				Collect tinymce instance to editors array
			*/
			var my_name = names[i_var];
			editors[i_var] = tinyMCE.get(my_name + '-editor');
			/*
				Check if wp_editor instance is initialized
			*/
			if(editors[i_var] != undefined && editors[i_var] != null)
			{
				/*
					If current wp_editor instance is initialized, then clear interval and bind 'change' and 'keyup' events
				*/
				clearInterval(intervals[i_var]);
				editors[i_var].on("change",function(){
					parse_value();
 				});
 				editors[i_var].on("keyup",function(){
 					parse_value();
 				});
			}
		},500,i);
	}
	/*
		Open first tab by default on page load
	*/
	open_tab(jQuery("ul.nav.nav-tabs").find("li").first().find("a"));
});
/*
	Wait for page to be rendered and then show it
*/
jQuery(function(){
	jQuery(".wrap-overlay").css("opacity","1");
	setTimeout(function(){
		jQuery(".wrap-overlay").animate({opacity:0},500,function(){
			jQuery(".wrap-overlay").remove();
			jQuery(".wrap").css("opacity","1");
		});
	},1500);
});
/*
	Bind click event handler to arrows so user will be able to scroll the tabs using the arrows
*/
jQuery(function(){
	jQuery(".arrows.dashicons.dashicons-arrow-right").click(function(){
		jQuery(".nav.nav-tabs").scrollLeft(jQuery(".nav.nav-tabs").scrollLeft() + 100);
	});
	jQuery(".arrows.dashicons.dashicons-arrow-left").click(function(){
		jQuery(".nav.nav-tabs").scrollLeft(jQuery(".nav.nav-tabs").scrollLeft() - 100);
	});
});
/*
	Converts all data from textarea of wp_editor / wp_editor's content to BASE64 and puts the value into hidden input related to wp_editor

	@returns VOID
*/
function parse_value()
{
	/*
		Loop through all wp_editor instances
	*/
	for(var i = 0; i < names.length; i++)
	{
		/*
			get wp_editor instance by name and check if it's initialized and not hidden
		*/
		var my_name = names[i];
		var mce = tinyMCE.get(my_name+'-editor');
		if(mce != null && mce != undefined && !mce.isHidden())
		{
			/*
				If wp_editor is not hidden and is initialized, then append all its contents to textarea used to init current wp_editor
			*/
			var temp = tinyMCE.get(my_name+'-editor').getContent();
			jQuery("#"+my_name + '-editor').val(temp);
		}
		/*
			Get all text from textarea used to initialize the wp_editor, convert it to BASE64 and put into hidden input field	
		*/
		var text = jQuery("#"+my_name + '-editor').val();
        var content = btoa(unescape(encodeURIComponent(text)));
        jQuery("#"+my_name).val(content);
     }
}
/*
	Opens tab with settings by provided identifier from initiator(i.e clicked button)

	@param initiator - an object of button that is passed onclick event, must contain opens-by attribute(with an ID of tab to open)

	@returns VOID
*/
function open_tab(initiator)
{
	var id = jQuery(initiator).attr("id");
	jQuery(".container-tab-contents").hide();
	jQuery(initiator).parent().parent().find("li").removeClass("active");

	jQuery(initiator).parent().addClass("active");
	jQuery(".container-tab-contents[opens-by="+id+"]").show();
}
