<?php
/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_accordion_slider
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); 

JFormHelper::loadFieldClass('list');

class JFormFieldK2Category extends JFormFieldList {
	/**
	 * The form field type.
	 * 
	 * @var    string
	 */
	public $type = 'K2Category';
	
	/**
	 * Constuctor
	 * 
	 * @param array $form
	 */
	public function __construct($form = array()){
		parent::__construct($form);
	}
	/**
	 *Custom Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 */
	protected function getInput()
	{
		return parent::getInput();
	}
	/**
	 * Method to get the field options for category
	 * Use the extension attribute in a form to specify the.specific extension for
	 * which categories should be displayed.
	 * Use the show_root attribute to specify whether to show the global category root in the list.
	 *
	 * @see JFormFieldCategory::getOptions()
	 *
	 * @return  array    The field option objects.
	 * 
	 */
	protected function getOptions() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('c.*')
			->from('#__k2_categories AS c')
			->where('trash = 0')
			->order('parent')
			->order('ordering');
		$db->setQuery($query);
		try {
			$rows = $db->loadObjectList();
			$children = array();
			if (count($rows)){
				foreach ($rows as $k=>$v){
					$v->title = $v->name;
	                $v->parent_id = $v->parent;
					$pt = $v->parent;
	                $list = @$children[$pt] ? $children[$pt] : array();
	                array_push($list, $v);
	                $children[$pt] = $list;
				}
			
				$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		        $options = array();
		
		        foreach ($list as $item)
		        {
		            $item->treename = JString::str_ireplace('&#160;', '- ', $item->treename);
		            $options[] = JHTML::_('select.option', $item->id, '   '.$item->treename);
		        }
		        $options = array_merge(parent::getOptions(), $options);
		        
		        return $options;
			}
			return array();
		}catch (Exception $e){
			$e->getMessage();
		}
		return array();
	}
}