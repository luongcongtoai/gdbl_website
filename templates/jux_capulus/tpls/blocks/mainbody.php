<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php
/**
 * Mainbody 2 columns, content in left, mast-col on top of 2 sidebars: content - sidebar
 */
$sidebar = 'sidebar';
$sidebar = $this->countModules($sidebar) ? $sidebar : false;


// detect layout
if ($sidebar) {
	$this->loadBlock('mainbody/sidebar-right');
} else {
	$this->loadBlock('mainbody/no-sidebar');
}

//should we show mastcol when there was no sidebar
?>
