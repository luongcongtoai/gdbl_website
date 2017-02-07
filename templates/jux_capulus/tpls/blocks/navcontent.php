<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- Nav content -->
<?php if ($this->countModules('navcontent')) : ?>
<section id="navcontent" class="wrap navcontent">
		<div class="navcontent<?php $this->_c('navcontent')?>">     
			<jdoc:include type="modules" name="<?php $this->_p('navcontent') ?>" style="raw" />
		</div>
</section>
<?php endif ?>
<!-- //Nav content -->