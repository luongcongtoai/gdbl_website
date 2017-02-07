<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->checkSpotlight('spotlight-3', 'spotlight3-1, spotlight3-2, spotlight3-3, spotlight3-4')) : ?>
<!-- SPOTLIGHT 3 -->
<section class="container t3-sl t3-sl-1">
  <?php 
  	$this->spotlight ('spotlight-3', 'spotlight3-1, spotlight3-2, spotlight3-3, spotlight3-4')
  ?>
</section>
<!-- //SPOTLIGHT 3 -->
<?php endif ?>