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

require_once __DIR__ . '/helper.php';

$params->def('greeting', 1);

$type      = modNooLoginHelper::getType();
$returnURL = modNooLoginHelper::getReturnURL($params, $type);
$user	= JFactory::getUser();

$noocaptcha = '';
if(JRequest::getCmd('option') !== 'com_users' || JRequest::getCmd('view') !== 'registration'){
	$userParams = JComponentHelper::getParams('com_users');
	JForm::addFormPath(JPATH_SITE.'/components/com_users/models/forms');
	$form = JForm::getInstance('com_users.registration','registration', array('control' => 'jform'), false, false);
	if(($c = $userParams->get('captcha')) != '')
		$form->setFieldAttribute('captcha','plugin',$c);

	$captcha = $form->getField('captcha');
	$noocaptcha = $captcha->input;
}

$document	= JFactory::getDocument();
$document->addStyleSheet('modules/mod_noo_login/assets/style.css');
$document->addScript('modules/mod_noo_login/assets/script.js');

require (JModuleHelper::getLayoutPath('mod_noo_login', $params->get('layout', 'default')));