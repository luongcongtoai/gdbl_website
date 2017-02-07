<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Mainbody 3 columns, content in center: content - sidebar1 - sidebar2
 */
$inner1  = 'inner-1';
$inner2  = 'inner-2';

$inner1 = $this->countModules($inner1) ? $inner1 : false;
$inner2 = $this->countModules($inner2) ? $inner2 : false;
?>

<div id="t3-mainbody" class="container t3-mainbody has-sidebar-right">
	<div class="row">

		<!-- MAIN CONTENT -->
		<div id="t3-content" class="t3-content col-xs-12 col-sm-8 col-md-8">
			<!-- MASSCOL 1 -->
			<?php if ($inner1) : ?>
				<div class="t3-inner t3-inner-1 <?php $this->_c($inner1) ?>">
					<jdoc:include type="modules" name="<?php $this->_p($inner1) ?>" style="T3Xhtml" />
				</div>
			<?php endif ?>
			<!-- //MASSCOL 1 -->
			<jdoc:include type="component" />
			<!-- MASSCOL 2 -->
			<?php if ($inner2) : ?>
				<div class="t3-inner t3-inner-2 <?php $this->_c($inner2) ?>">
					<jdoc:include type="modules" name="<?php $this->_p($inner2) ?>" style="T3Xhtml" />
				</div>
			<?php endif ?>
			<!-- //MASSCOL 2 -->
			
		</div>
		<!-- //MAIN CONTENT -->

		<div class="t3-sidebar t3-sidebar-right col-xs-12 col-sm-4 col-md-4">
			<jdoc:include type="modules" name="sidebar" style="T3Xhtml" />
		</div>

	</div>
</div> 