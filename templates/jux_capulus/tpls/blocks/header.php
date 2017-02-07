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
?>

<!-- HEADER -->
<header id="t3-header" class="container t3-header">
	<div class="row">
		<!-- Language Switcher-->
		<div class="col-xs-12 col-sm-12 col-md-12 jux-languages">
			<?php if ($this->countModules('languages')) : ?>
			<!-- HEAD SEARCH -->
					<jdoc:include type="modules" name="<?php $this->_p('languages') ?>" style="raw" />
			<!-- //HEAD SEARCH -->
			<?php endif ?>
		</div>
		<!-- //Language Switcher-->
		<!-- LOGO -->
		<div class="col-xs-12 <?php echo $logosize ?> logo col-md-12">
			<div class="logo-<?php echo $logotype ?>">
				<a href="<?php echo JURI::base(true) ?>" title="<?php echo strip_tags($sitename) ?>">
					<?php if($logotype == 'image'): ?>
						<img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
					<?php endif ?>
					<span><?php echo $sitename ?></span>
				</a>
				<small class="site-slogan"><?php echo $slogan ?></small>
			</div>
		</div>
		<!-- //LOGO -->
		<!-- Find Capulus-->
		<div class="col-xs-12 col-sm-12 col-md-12">
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
