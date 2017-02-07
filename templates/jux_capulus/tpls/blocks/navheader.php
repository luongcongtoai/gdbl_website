<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if ($this->countModules('navheader')) : ?>
<!-- NAV HEADER -->
<section class="wrap t3-navheader <?php $this->_c('navheader') ?>">
	<div class="container">
		<jdoc:include type="modules" name="<?php $this->_p('navheader') ?>" style="T3xhtml" />
	</div>
</section>
<!-- //NAV HEADER -->
<?php endif; ?>