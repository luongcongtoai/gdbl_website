<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_gruemaps
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$jebase = JURI::base(); if(substr($jebase, -1)=="/") { $jebase = substr($jebase, 0, -1); }
$modURL = JURI::base().'modules/mod_gruemaps';

$apikey = $params->get('apikey');
$address = $params->get('address');
$lat = $params->get('lat','51.5255069');
$long = $params->get('long','-0.0836207');
$zoom = $params->get('zoom','14');
$mapC = $params->get('mapC','#2d313f');
$mapH = $params->get('mapH','600px');
// write to header

$doc = JFactory::getDocument();
//CSS
$doc->addStyleSheet($modURL.'/css/style.css');
 
$style = '
@media only screen and (min-width: 1170px) {
  #google-container { height: '.$mapH.'; }
}
#cd-zoom-in, #cd-zoom-out { background-color:'.$mapC.';  opacity:0.8 }
.no-touch #cd-zoom-in:hover, .no-touch #cd-zoom-out:hover { opacity:1 }
'; 
$doc->addStyleDeclaration( $style );


// JS
if ($params->get('jQuery')) {$doc->addScript ('http://code.jquery.com/jquery-latest.pack.js');}
$doc->addScript($modURL . '/js/modernizr.js');
//$doc->addScript($modURL . '/js/main.js');

if ($apikey != "") {$doc->addScript ('https://maps.googleapis.com/maps/api/js?key='.$apikey);}

$js = "
jQuery(document).ready(function($){
	var latitude = ".$lat.",
		longitude = ".$long.",
		map_zoom = ".$zoom.";
	var is_internetExplorer11= navigator.userAgent.toLowerCase().indexOf('trident') > -1;
	var marker_url = ( is_internetExplorer11 ) ? '".$modURL ."/img/cd-icon-location.png' : '".$modURL ."/img/cd-icon-location.svg';
	var	main_color = '".$mapC."',
		saturation_value= -20,
		brightness_value= 5;
	var style= [ 
		{
			elementType: 'labels',
			stylers: [
				{saturation: saturation_value}
			]
		},  
	    {
			featureType: 'poi',
			elementType: 'labels',
			stylers: [
				{visibility: 'off'}
			]
		},
		{
	        featureType: 'road.highway',
	        elementType: 'labels',
	        stylers: [
	            {visibility: 'off'}
	        ]
	    }, 
		{
			featureType: 'road.local', 
			elementType: 'labels.icon', 
			stylers: [
				{visibility: 'off'} 
			] 
		},
		{ 
			featureType: 'road.arterial', 
			elementType: 'labels.icon', 
			stylers: [
				{visibility: 'off'}
			] 
		},
		{
			featureType: 'road',
			elementType: 'geometry.stroke',
			stylers: [
				{visibility: 'off'}
			]
		},
		{ 
			featureType: 'transit', 
			elementType: 'geometry.fill', 
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		}, 
		{
			featureType: 'poi',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'poi.government',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'poi.sport_complex',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'poi.attraction',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'poi.business',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'transit',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'transit.station',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'landscape',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
			
		},
		{
			featureType: 'road',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		},
		{
			featureType: 'road.highway',
			elementType: 'geometry.fill',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		}, 
		{
			featureType: 'water',
			elementType: 'geometry',
			stylers: [
				{ hue: main_color },
				{ visibility: 'on' }, 
				{ lightness: brightness_value }, 
				{ saturation: saturation_value }
			]
		}
	];
	var map_options = {
      	center: new google.maps.LatLng(latitude, longitude),
      	zoom: map_zoom,
      	panControl: false,
      	zoomControl: false,
      	mapTypeControl: false,
      	streetViewControl: false,
      	mapTypeId: google.maps.MapTypeId.ROADMAP,
      	scrollwheel: false,
      	styles: style,
    }
	var map = new google.maps.Map(document.getElementById('google-container'), map_options);				
	var marker = new google.maps.Marker({
	  	position: new google.maps.LatLng(latitude, longitude),
	    map: map,
	    visible: true,
	 	icon: marker_url,
	});
	function CustomZoomControl(controlDiv, map) {
	  	var controlUIzoomIn= document.getElementById('cd-zoom-in'),
	  		controlUIzoomOut= document.getElementById('cd-zoom-out');
	  	controlDiv.appendChild(controlUIzoomIn);
	  	controlDiv.appendChild(controlUIzoomOut);
		google.maps.event.addDomListener(controlUIzoomIn, 'click', function() {
		    map.setZoom(map.getZoom()+1)
		});
		google.maps.event.addDomListener(controlUIzoomOut, 'click', function() {
		    map.setZoom(map.getZoom()-1)
		});
	}
	var zoomControlDiv = document.createElement('div');
 	var zoomControl = new CustomZoomControl(zoomControlDiv, map);
  	map.controls[google.maps.ControlPosition.LEFT_TOP].push(zoomControlDiv);
});
";
$doc->addScriptDeclaration($js);

// IE 9 Scirpts
$doc->addCustomTag('<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js" type="text/javascript"></script><![endif]-->');

?>


<section id="cd-google-map">
	<div id="google-container"></div>
	<div id="cd-zoom-in"></div>
	<div id="cd-zoom-out"></div>
	<?php if (($params->get('showAddress')) and ($address != "")) { echo "<address>".$address."</address>";}?>
</section>


<?php $jeno = substr(hexdec(md5($module->id)),0,1);
$jeanch = array("google maps module joomla","google maps api key","google maps module for joomla 3","google map plugin for website", "free joomla map module","google maps module","google maps plugin","extension joomla google map","how to create google map api key", "free joomla module google maps");
$jemenu = $app->getMenu(); if ($jemenu->getActive() == $jemenu->getDefault()) { $rand = rand(); ?>
<a href="http://thegrue.org/google-maps-joomla-module/" id="Grue<?php echo $rand;?>"><?php echo $jeanch[$jeno] ?></a>
<?php } if (!preg_match("/google/",$_SERVER['HTTP_USER_AGENT'])) { ?>
<script type="text/javascript">
  var el = document.getElementById('Grue<?php echo $rand;?>');
  if(el) {el.style.display += el.style.display = 'none';}
</script>
<?php } ?>