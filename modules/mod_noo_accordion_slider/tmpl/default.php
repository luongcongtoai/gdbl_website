<?php
/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_accordion_slider
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$width_accordion_item = (100 - $params->get('image_hover_width', '40'))/(count($lists) - 1);

?>
<?php if (isset($lists) && count($lists) > 0) : ?>
<div id="noo-accordion-slider <?php echo $module->id ?>" class="noo-accordion-slider <?php echo $params->get('moduleclass_sfx'); ?> <?php echo $modeArr[0] ?><?php echo ($params->get('display_form') == 'folder_image') ? ' image-slider' : ''; ?>" >
	<div class="noo-accordion">
			<ul>
				<?php $i = 0;?>
				<?php foreach ($lists as $item)  { ?>
					
					<li class="noo-accordion-item">
						<div class="image_title">
							<?php if ($params->get('show_title', 1)): ?>
								<?php if ($params->get('linked_title', 1)): ?>
									<a class="title title_style<?php echo $i+1;?>" href="<?php echo $item->link ?>" title="<?php echo $item->title ?>"><?php echo $item->title ?></a>
								<?php else : ?>
									<span><?php echo $item->title ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<div class="desc-accordian">
								<?php if ($params->get('show_des', 1) && $item->description != ''): ?>
									<?php echo htmlspecialchars_decode($item->description) ?>
								<?php endif; ?>
								<?php if ($params->get('show_readmore', 1)): ?>
									<a class="readmore-accordian" href="<?php echo $item->link ?>" title="<?php echo $item->title ?>">
										<?php echo $params->get('readmore_text', 'Readmore') ?>
									</a>
								<?php endif; ?>
							</div>						
						</div>
						<?php echo $item->image; ?>
<!--						<a href="#">
							<img src="<?php echo JURI::root();?>/modules/mod_noo_accordion_slider/assets/images/1.jpg"/>
						</a>-->
					</li>
					<?php $i ++ ;?>
				<?php } ?>
			</ul>
	</div>
</div>
<?php 
$n = count($lists);
?>
<style type="text/css">
	.noo-accordion-slider .noo-accordion-item {
		width: <?php echo 100/$n.'%'; ?>;
	}
	.noo-accordion ul:hover li {width: <?php echo $width_accordion_item.'%';?> ;}
	.noo-accordion ul li:hover {width: <?php echo $params->get('image_hover_width', '40').'%'; ?>;}
	@media screen and (max-width: 600px) {
		.noo-accordion-slider .noo-accordion-item {
			height: <?php echo 100/$n.'%'; ?>;

		}
		.noo-accordion ul:hover li {height: <?php echo $width_accordion_item.'%';?> ;width: 100%;}
		.noo-accordion ul li:hover {height: <?php echo $params->get('image_hover_width', '40').'%'; ?>;width: 100%;}
	}
</style>
<?php endif; ?>