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
require_once __DIR__ . '/helper.php';

$lists = modNooAccordionSliderHelper::getList($params);

$document	= JFactory::getDocument();

//include css
$document->addStyleSheet('modules/mod_noo_accordion_slider/assets/css/style.css');

$modeArr		= array();
$mode			= $params->get('slide_mode', 'horizontal_left');
$image_slider	= $params->get('display_form') == 'folder_image' ? '1' : '0';
$modeArr		= explode('_', $mode);
$visible		= ($params->get('visible', 1) > count($lists) ) ? count($lists) : $params->get('visible', 1);

require (JModuleHelper::getLayoutPath('mod_noo_accordion_slider',$params->get('layout', 'default')));