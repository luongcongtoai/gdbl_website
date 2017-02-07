<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->checkSpotlight('spotlight-2', 'spotlight2-1, spotlight2-2, spotlight2-3, spotlight2-4')) : ?>
<!-- SPOTLIGHT 2 -->
<section class="container t3-sl t3-sl-1">
  <?php 
  	$this->spotlight ('spotlight-2', 'spotlight2-1, spotlight2-2, spotlight2-3, spotlight2-4')
  ?>
</section>
<!-- //SPOTLIGHT 2 -->
<?php endif ?>