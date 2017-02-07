/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_login
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

var nooboxes = [];
var noooverlay = null;
function nooShowBox(element){
	if(!noooverlay){
		jQuery(element).before('<div id="noologin-overlay"></div>');
		noooverlay = jQuery('#noologin-overlay');
		noooverlay.css({
			opacity:0.01
		}).fadeIn();
		noooverlay.click(function() {
			nooboxes.each(function(box){
				if(box.css('display') == 'block'){
					box.hide();
				}
			});
			noooverlay.hide();
		});
	}
	var box = jQuery(element);
	if(!box)
		return;
	if (!nooboxes.contains(box)) {
		nooboxes.push(box);
	}
	
	nooboxes.each(function(el){
		el.hide();
	});
	noooverlay.show();
	box.show();
}