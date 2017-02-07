<?php
/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_login
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

abstract class modNooLoginHelper {
	
	/**
     * Get url return after login or logout
     * 
     * @param object params of  noologin module
     * @param string $type
     * @return string url redirect
     */
	public static function getReturnURL($params, $type){
		
		if ($itemid = $params->get($type)) {
			$menu = JFactory::getApplication()->getMenu();
            $item = $menu->getItem($itemid);
            if ($item){
            	 $url = JRoute::_($item->link . '&Itemid=' . $itemid, false);
            }else{
                $url = JFactory::getURI()->toString(array('path', 'query', 'fragment'));
            }
		}else{
			$url = JFactory::getURI()->toString(array('path', 'query', 'fragment'));
		}
		return base64_encode($url);
	}
	
	/**
     * Get type user action
     * @return string type
     */
    public static function getType()
    {
        return (!JFactory::getUser()->get('guest')) ? 'logout' : 'login';
    }
}