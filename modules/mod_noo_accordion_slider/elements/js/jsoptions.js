/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_accordion_slider
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

/**
 * Function for hide options.
 * 
 * @param   array  sub_fields  The list of fields to Hide.
 */
function js_HideOptions(sub_fields) {
	if((/^\s*$/).test(sub_fields)) {
		return;
	}

	fields = sub_fields.split(',');
	for(var i = 0; i < fields.length; i ++){
		js_HideOption(fields[i]);
	}
}

/**
 * Function for show options.
 * 
 * @param   array  sub_fields  The list of fields to Show.
 */
function js_ShowOptions(sub_fields) {
	if((/^\s*$/).test(sub_fields)) {
		return;
	}

	fields = sub_fields.split(',');
	
	for(var i = 0; i < fields.length; i ++){
		if((/^\s*$/).test(fields[i])) {
			continue;
		}
		js_ShowOption(fields[i]);
	}
}

/**
 * Function for show options.
 * 
 * @param   array  sub_fields  The list of fields to Show.
 */
function js_ShowOptionsByControl(control_field, sub_fields_array) {
	if((/^\s*$/).test(control_field)) {
		return;
	}
	
	if($('jform_params_'+ control_field) == null){
		return;
	}
	
	var key = $('jform_params_'+ control_field).get("value");
	var sub_fields = sub_fields_array[key];

	if((/^\s*$/).test(sub_fields)) {
		return;
	}

	fields = sub_fields.split(',');
	
	for(var i = 0; i < fields.length; i ++){
		if((/^\s*$/).test(fields[i])) {
			continue;
		}
		js_ShowOption(fields[i]);
	}
}

/**
 * Function for Show one options
 * 
 * @param   string  field_name  Name of Field to show.
 */
function js_ShowOption(field_name) {
	var field	= $('jform_params_'+ field_name);
	if(field == null) {
		field	= $('jform_params_'+ field_name + '-lbl');
	}
	
	if(field == null) {
		return;
	}

	// Joomla 3.0
	var control	= field.getParent('div.control-group');
	
	// Joomla 2.5 field
	if(control == null) {
		control = field.getParent('li');
	}
	
	// Show
	if(control !== null && control.hasClass('hide')) {
		control.removeClass('hide');
	}
}

/**
 * Function for Hide one options
 * 
 * @param   string  field_name  Name of Field to hide.
 */
function js_HideOption(field_name) {
	var field	= $('jform_params_'+ field_name);
	if(field == null) {
		field	= $('jform_params_'+ field_name + '-lbl');
	}
	
	if(field == null) {
		return;
	}

	// Joomla 3.0
	var control	= field.getParent('div.control-group');
	
	// Joomla 2.5 field
	if(control == null) {
		control = field.getParent('li');
	}
	
	// Hide
	if(control !== null && !control.hasClass('hide')) {
		control.addClass('hide');
	}
}
/**
 * Function for Toggler Disabled Params.
 * 
 * @param   array  sub_fields  The list of fields to Show.
 */
function js_TogglerDisabledParams(control,elementsArr){
	
	if((/^\s*$/).test(control)) {
		return;
	}
	
	if($('jform_params_'+ control) == null){
		return;
	}
}

/**
 * Class for Set up Accordion inside a panel of Joomla module's option.
 */
JUX_Accordion_Options = new Class ( {
	toggler_class: 'jux_toggler',
	element_class: 'jux_element',
	pane_id_list: [],

	initialize: function(toggler_class, element_class){
        this.toggler_class = toggler_class;
        this.element_class = element_class;
    },

	addPane: function(pane_id) {
		if(this.pane_id_list.length > 0) {
			last_pane_id = this.pane_id_list.getLast();
			this.addAccordionElement(last_pane_id, pane_id);
		}
		
		this.pane_id_list.push(pane_id);
	},
	
	addAccordionElement: function(start_pane_id, end_pane_id) {
		var isJoomla30		= true;
		// Get this control
		// Joomla 3.0
		var start_field_li	= $(start_pane_id).getParent('div').getParent('div.control-group');
		
		// Check for Joomla 2.5
		if(start_field_li == null) {
			start_field_li	= $(start_pane_id).getParent('li');
			isJoomla30		= false;
		}
		
		if(start_field_li == null) {
			return;
		}
		
		var end_field_li	= isJoomla30 ? $(end_pane_id).getParent('div').getParent('div.control-group') : $(end_pane_id).getParent('li');

		// Inject start DIV before this li
		var paneDIV			= new Element('div');
		paneDIV.set('class', 'jux_option_element ' + this.element_class);

		
		// Temp element for loop, we'll need two
		var temp_li			= isJoomla30 ? start_field_li.getNext('div.control-group') : start_field_li.getNext('li');
		var temp_li_2		= temp_li;

		// Loop through li
		while (temp_li != null && !temp_li.match(end_field_li)) {
			temp_li_2	= isJoomla30 ? temp_li.getNext('div.control-group') : temp_li.getNext('li');
			paneDIV.wraps(temp_li);
			temp_li		= temp_li_2;
		}
	},
	
	start: function(){
		this.correctTogglerClass();
		new Fx.Accordion($$('.' + this.toggler_class),$$('.' + this.element_class), {
			opacity: 0,
			onActive: function(toggler, section) {
				toggler.removeClass('jux_option_toggler-close');
				toggler.addClass('jux_option_toggler');
				
//				section.addClass('jux_option_element-active');
			},
			onBackground: function(toggler, section) {
//				section.removeClass('jux_option_element-active');

				toggler.removeClass('jux_option_toggler');
				toggler.addClass('jux_option_toggler-close');
			}
		});
	},
	
	correctTogglerClass: function() {
		var togglers = document.getElements('.'+this.toggler_class);
		togglers.each(function(toggler){
			toggler.getParent('div').removeClass('control-label');
			toggler.getParent('div').addClass('jux_toggler-bound');
		});
	}
	
});
