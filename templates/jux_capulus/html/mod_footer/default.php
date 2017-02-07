<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$menu = $app->getMenu();
?>
<div class="footer1<?php echo $moduleclass_sfx ?>"><?php echo $lineone; ?> 
<?php /*Designed by JoomlaUX.com <a <?php if ($menu->getActive() != $menu->getDefault()) : ?>rel="nofollow"<?php endif ?> title="Premium Responsive Joomla Templates" href="http://www.joomlaux.com/jux-templates.html" target="_blank">Premium Responsive Joomla Templates</a>*/?>.</div>
<?php /*<small><?php echo JText::_( 'MOD_FOOTER_LINE2' ); ?></small>*/?>
