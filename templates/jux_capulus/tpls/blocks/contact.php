<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// get params
$sitename  = $this->params->get('sitename');
$slogan    = $this->params->get('slogan', '');
$logotype  = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', 'templates/' . T3_TEMPLATE . '/images/logo.png') : '';

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

$logosize = 'col-sm-12';
//if ($headright = $this->countModules('head-search or languageswitcherload')) {
	//$logosize = 'col-sm-8';
//}
?>

<!-- HEADER -->
<header id="t3-header" class="container t3-header">
	<div class="row">

		<!-- Find Capulus-->
		<div class="col-xs-12 col-sm-12">
			<?php if ($this->countModules('find-capulus')) : ?>
			<!-- HEAD SEARCH -->
					<jdoc:include type="modules" name="<?php $this->_p('find-capulus') ?>" style="raw" />
			<!-- //HEAD SEARCH -->
			<?php endif ?>
		</div>
		<!-- //Find Capulus-->
	</div>
</header>
<!-- //HEADER -->
