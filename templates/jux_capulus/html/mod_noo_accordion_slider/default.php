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
<?php if (isset($lists) && $n = count($lists)) : ?>
<div id="noo-accordion-slider <?php echo $module->id ?>" class="noo-accordion-slider <?php echo $params->get('moduleclass_sfx'); ?> <?php echo $modeArr[0] ?><?php echo ($params->get('display_form') == 'folder_image') ? ' image-slider' : ''; ?>" >
	<div class="noo-accordion">
			<ul>
				<?php for ($i=0; $i<count($lists);$i++) { ?>
					<li class="noo-accordion-item">
						<div class="image_title">
							<?php if ($params->get('show_title', 1)): ?>
								<?php if ($params->get('linked_title', 1)): ?>
									<a class="title title_style<?php echo $i+1;?>" href="<?php echo $lists[$i]->link ?>" title="<?php echo $lists[$i]->title ?>"><?php echo $lists[$i]->title ?></a>
								<?php else : ?>
									<span><?php echo $lists[$i]->title ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<div class="desc-accordian">
								<?php if ($params->get('show_des', 1) && $lists[$i]->description != ''): ?>
									<?php echo htmlspecialchars_decode($lists[$i]->description) ?>
								<?php endif; ?>
								<?php if ($params->get('show_readmore', 1)): ?>
									<a class="readmore-accordian" href="<?php echo $lists[$i]->link ?>" title="<?php echo $lists[$i]->title ?>">
										<?php echo $params->get('readmore_text', 'Readmore') ?>
									</a>
								<?php endif; ?>
							</div>						
						</div>
						<?php echo $lists[$i]->image; ?>
					</li>
				<?php } ?>
			</ul>
	</div>
</div>
<style type="text/css">
	.noo-accordion-slider .noo-accordion-item {
		width: <?php echo 100/$n.'%'; ?>;
	}
	.noo-accordion ul:hover li {width: <?php echo $width_accordion_item.'%';?> ;}
	.noo-accordion ul li:hover {width: <?php echo $params->get('image_hover_width', '40').'%'; ?>;}
	@media screen and (max-width: 767px) {
		.noo-accordion-slider .noo-accordion-item {
			height: <?php echo 100/$n.'%'; ?>;

		}
		.noo-accordion ul:hover li {height: <?php echo $width_accordion_item.'%';?> ;width: 100%;}
		.noo-accordion ul li:hover {height: <?php echo $params->get('image_hover_width', '40').'%'; ?>;width: 100%;}
	}
</style>
<?php endif; ?>