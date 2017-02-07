<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- Main container -->
<?php if ($this->countModules('slideshow')) : ?>
	<section id="slideshow" class="wrap slideshow">
			<jdoc:include type="modules" name="<?php $this->_p('slideshow') ?>" style="slideshow" />
	</section>
<?php endif; ?>
<!-- //Main container -->