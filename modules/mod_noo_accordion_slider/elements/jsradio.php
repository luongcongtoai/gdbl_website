<?php
/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_accordion_slider
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('radio');

/**
 * Create Radio List Button. With the ability to show/hide sub-options.
 * Example xml:
 * <field
 * 	name="mod_js_show_hide"
 * 	type="JSRadio"
 * 	default="1"
 * 	label="MOD_JS_LABEL"
 * 	description="MOD_JS_DESC">
 * 	<option value="1" sub_fields="mod_yes_field_1,mod_yes_field_2">JYES</option>
 * 	<option value="0" sub_fields="mod_no_field_1,mod_no_field_2">JNO</option>
 * </field>
 */
class JFormFieldJSRadio extends JFormFieldRadio {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'JSRadio';
	
	/**
	 * Active sub-fields.
	 * 
	 * @var		string
	 */
	protected $active_sub_fields = '';
	
	/**
	 * List of all sub-fields
	 * 
	 * @var		string
	 */
	protected $sub_fields_list = array();

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		if (!defined ('JS_MODULE_OPTIONS_ASSETS')) {
			define ('JS_MODULE_OPTIONS_ASSETS', 1);
			$uri = str_replace("\\","/",str_replace( JPATH_SITE, JURI::base (), dirname(__FILE__) ));
			$uri = str_replace("/administrator/", "", $uri);
			JHTML::script($uri.'/js/jsoptions.js');
			//JHTML::stylesheet($uri.'/css/jsoptions.css');
		}

		$html = parent::getInput();
		$this->onload_script();
		
		return $html;
	}

	/**
	 * Method to get the script onload
	 * 
	 * @return blank
	 */
	private function onload_script() {
		?>
		<script type="text/javascript">
			var js_subfield_<?php echo $this->element['name']; ?> = "<?php echo implode(',', $this->sub_fields_list); ?>";
			window.addEvent('load', function() {
				js_HideOptions(js_subfield_<?php echo $this->element['name']; ?>);
				js_ShowOptions('<?php echo $this->active_sub_fields; ?>');
				if($('jform_params_display_form').get('value') == 'folder_image'){
					js_HideOptions('linked_title,description_max_chars,readmore_text');
				}
				if($('jform_params_show_image1').checked == false){
					js_HideOptions('thumbnail_mode,use_ratio,image_width,image_height');
					js_HideOptions('description_max_chars');
				}
			});
		</script>
		<?php

		return;
	}

	/**
	 * Override getOptions Method to get sub fields list.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions() {
		// Initialize variables.
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
							'select.option', (string) $option['value'], trim((string) $option), 'value', 'text', ((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Get sub_fields.
			$sub_fields = str_replace("\n", '', trim($option['sub_fields']));
			if(!empty($sub_fields)) {
				$this->sub_fields_list = array_merge($this->sub_fields_list, array((string)$option['value'] => $sub_fields));
			}
			
			// Check if it's selected
			if($option["value"] == $this->value) {
				$this->active_sub_fields = $sub_fields;
			}

			// Set some JavaScript option attributes.
			$onclick = !empty($option['onclick']) ? (string) $option['onclick'] : '';

			// Add default onclick
			$onclick .= ' js_HideOptions(js_subfield_'.$this->element['name'].');';
			$onclick .= "js_ShowOptions('$sub_fields');";

			$tmp->onclick = $onclick;

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}

}