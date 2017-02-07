/*-------------------------------*/
/*  Do Not edit below this line  */
/*-------------------------------*/
var classAdder;
var markerGroups = "";
var map = null;
var searchCenter;
var addressSet;
var overlayControls;
var wikiLayer;
var photoLayer;
var startAddress;
var userStartAddress;
var userFormattedAddress;
var directions;
var myPano;
var jsonGroups;
var mapDiv;
var mapLoading;
var ddBoxDiv;
var batchGeoLat = [];
var batchGeoLng = [];
var lastInfoWindow;
var directionDisplay;
var directionsService;
var geoCenter;
var geoLng;
var geoLat;
var listRight;
var travMode;
var overlayDiv;
var directionsService = new google.maps.DirectionsService();
var directionsDisplay = new google.maps.DirectionsRenderer();

	
function setupAddress() {
	if (typeof poiZoomLevel === 'undefined') {
		zoomLevel = defaultZoomLevel;
	} else {
		zoomLevel = poiZoomLevel;
	}
	if (useinfoHTML == true)
		{
		infoHTML = CongifInfoHTML;
		}else{
		infoHTML = defaultInfoHTLM;
		}
}

function centerBox(child, parent) {
	var h = document.getElementById(child).offsetHeight;
	var a = Math.round(parseInt(document.getElementById(parent).offsetHeight, 10) / 2);
	var b = Math.round(h / 2);
	var c = (a - b) + "px";
	document.getElementById(child).style.top = c;
	var w = document.getElementById(child).offsetWidth;
	var x = Math.round(parseInt(document.getElementById(parent).offsetWidth, 10) / 2);
	var y = Math.round(w / 2);
	var z = x - y;
	document.getElementById(child).style.left = z + "px";
}

function directionsChange(){
	var dirSelect = document.getElementById('directionsControl');
	if(dirSelect.options[dirSelect.selectedIndex].value == 'DRIVING') {
		travMode = google.maps.DirectionsTravelMode.DRIVING;
	} else {
		travMode = google.maps.DirectionsTravelMode.WALKING;
	}
}

function hideInfoBox() {
	document.getElementById("infoBox").style.display = "none";
}

function geotarget() {
	navigator.geolocation.getCurrentPosition ( 
		function (position) {
			if(document.getElementById('geoTarget') === undefined) {
				geoTarget = document.createElement('a');
				geoTarget.setAttribute('onclick', 'javascript: geotarget(); return false');
				geoTarget.setAttribute('id', 'geoTarget');
				overlayDiv = document.getElementById('overlayControl');
				overlayDiv.appendChild(geoTarget);
			}
			//alert (position.coords.latitude + ":" + position.coords.longitude);
			var geoCenter = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			map.setCenter(geoCenter);
			startAddress = position.coords.latitude + ", " + position.coords.longitude;
			if(userStartAddress != startAddress) {
				userStartAddress = startAddress;
				getUserFormattedAddress(geoLat, geoLng);
			}
			createMarker(geoCenter, 0, defaultHTML, "pin", pinIcon, "");
			getCategories(0);
			if (mapExtra === true) {
				mapPost();
			}
		});
}

function getScrollBarWidth () {
	var inner = document.createElement('p');
	inner.style.width = "100%";
	inner.style.height = "200px";

	var outer = document.createElement('div');
	outer.style.position = "absolute";
	outer.style.top = "0px";
	outer.style.left = "0px";
	outer.style.visibility = "hidden";
	outer.style.width = "200px";
	outer.style.height = "150px";
	outer.style.overflow = "hidden";
	outer.appendChild (inner);

	document.body.appendChild (outer);
	var w1 = inner.offsetWidth;
	outer.style.overflow = 'scroll';
	var w2 = inner.offsetWidth;
	if (w1 == w2) {
		w2 = outer.clientWidth;
	}
	document.body.removeChild (outer);
	return (w1 - w2);
}

function togglelist() {
	var toggleListElem = document.getElementById(categoriesList);
	if (toggleListElem.parentNode.style.width == listExpandWidth){
		pAddClass(document.getElementById("listToggle"), "listHidden");
		pRemoveClass(document.getElementById("listToggle"), "listVisible");
		toggleListElem.parentNode.style.width = listCollapseWidth;
	} else {
		pRemoveClass(document.getElementById("listToggle"), "listHidden");
		pAddClass(document.getElementById("listToggle"), "listVisible");
		toggleListElem.parentNode.style.width = listExpandWidth;
	}
}
function infoBox() {
	mapLoading = document.createElement('div');
	mapDiv.appendChild(mapLoading);
	mapLoading.style.zIndex = 100;
	mapLoading.setAttribute('id', 'mapLoading');
	centerBox("mapLoading", mapDivID);
	if (infoHTML !== "") {
		var infoBoxDiv = document.createElement('div');
		infoBoxDiv.style.position = "relative";
		infoBoxDiv.setAttribute('id', 'infoBox');
		mapDiv.appendChild(infoBoxDiv);
		infoBoxDiv.innerHTML = infoHTML;
		infoBoxDiv.style.zIndex = "1";
		infoBoxDiv.style.top = (mapDiv.offsetHeight - infoBoxDiv.offsetHeight) + "px";
		var infoBoxClose = document.createElement('a');
		infoBoxClose.setAttribute('id', 'infoBoxClose');
		infoBoxClose.style.position = "absolute";
		infoBoxDiv.appendChild(infoBoxClose);
		infoBoxClose.style.top = "4px";
		infoBoxClose.style.left = "4px";
		infoBoxClose.onclick = function() { 
			hideInfoBox();
		};
	}
	overlayDiv = document.createElement('div');
	overlayDiv.setAttribute('id', 'overlayControl');
	mapDiv.appendChild(overlayDiv);
	overlayDiv.style.zIndex = 2;
  
	var overlayHTML = "";
	if (searchControls === true) {
		overlayHTML = overlayHTML + '<form id="searchForm" action="#" onsubmit="findAddress(this.address.value); return false">';
		overlayHTML = overlayHTML + '<input id="searchTxt" type="text" size="20" name="address" placeholder="' + searchControlText + '" />';
		overlayHTML = overlayHTML + '<input id="searchButton" type="submit" value="Go" /> </form>';
	}
	//geoloation
	if (useGeolocation === true){
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition( 
 
				function (position) {  
					//Did we get the position correctly?
					//alert (position.coords.latitude + ":" + position.coords.longitude);
					geoTarget = document.createElement('a');
					geoTarget.setAttribute('onclick', 'javascript: geotarget(); return false');
					geoTarget.setAttribute('id', 'geoTarget');
					geoCenter = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					geoLat = position.coords.latitude;
					geoLng = position.coords.longitude;
				
					overlayDiv.appendChild(geoTarget);
					if (autoGeolocation === true) {
						map.setCenter(geoCenter);
						startAddress = geoLat + ", " + geoLng;
						if(userStartAddress != startAddress) {
							userStartAddress = startAddress;
							getUserFormattedAddress(geoLat, geoLng);
						}
//						createMarker(geoCenter, 0, "You are here", "pin", pinIcon, "");
						createMarker(geoCenter, 0, "" + defaultHTML, "pin", pinIcon, "");
						getCategories(0);
						if (mapExtra === true) {
							mapPost();
						}
					}
				}, 
				function (error)
				{
					switch(error.code) 
					{
						case error.TIMEOUT:
							//					alert ('Timeout');
							break;
						case error.POSITION_UNAVAILABLE:
							//					alert ('Position unavailable');
							break;
						case error.PERMISSION_DENIED:
							//					alert ('Permission denied');
							break;
						case error.UNKNOWN_ERROR:
							//					alert ('Unknown error');
							break;
						default:
							//					alert ('Unknown error');
							break;
					}
				}
				);
		}
	}
//
//	if(directionControls === true) {
//		
//		var driveSelect = "";
//		var walkSelect = "";
//		if (directionsMode  == 'DRIVING'){
//			driveSelect = " selected='selected'";
//		} else {
//			walkSelect = " selected='selected'";
//		}
//		
//		overlayHTML = overlayHTML + "<select id='directionsControl' onchange='javascript:directionsChange();'>";
//		overlayHTML = overlayHTML + '<option value="DRIVING"'+ driveSelect +'>Driving</option>';
//		overlayHTML = overlayHTML + '<option value="WALKING"'+ walkSelect +'>Walking</option>';
//		overlayHTML = overlayHTML + '</select>';
//
//	}
	overlayDiv.innerHTML = overlayHTML;
	overlayDiv.style.position = "absolute";
	if (toggleList === true){
		var listElem = document.getElementById(categoriesList);
		var listToggle = document.createElement('a');
//		listToggle.innerHTML = "Hide List";
		listToggle.innerHTML = "List Point Of Interest";
		listToggle.setAttribute("ID", "listToggle");
		//listToggle.onclick = function() { togglelist() };
//		listToggle.setAttribute("onclick", "togglelist();");
		listToggle.style.cursor = "pointer";
		listElem.parentNode.insertBefore(listToggle, listElem);
		//compensate for scroll bars
		if (listElem.offsetHeight > document.getElementById(mapDivID).offsetHeight){
			listCollapseWidth = parseInt(listCollapseWidth, 10) + getScrollBarWidth();
			listCollapseWidth =  listCollapseWidth + "px";
			listExpandWidth = parseInt(listExpandWidth, 10) + getScrollBarWidth();
			listExpandWidth =  listExpandWidth + "px";
		}
		listElem.parentNode.style.width = listCollapseWidth;
  
		if (showListExpanded === true) {
			pAddClass(listToggle, "listVisible");
			listElem.parentNode.style.width = listExpandWidth;
		} else {
			pAddClass(listToggle, "listHidden");
		}
	}
	mapLoading.style.display = "none";
}

function getUserFormattedAddress(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
	var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': latlng}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				userFormattedAddress = results[0].formatted_address;
			}
		}
    });
}

function iwHandle(infowindow, map, marker, style) {

	if (infoWindowDivID !== ""){
		externalDiv = document.getElementById(infoWindowDivID);
		externalDiv.innerHTML = infowindow.content;
	} else {
		infowindow.open(map,marker);
		lastInfoWindow = infowindow;
	}
}

function isTouchDevice(){
	try{
		document.createEvent("TouchEvent");
		return true;
	}catch(e){
		return false;
	}
}

function mobileScroll(id){
	if(isTouchDevice()){ 
		var el = document.getElementById(id);
		var scrollStartPosY=0;
		var scrollStartPosX=0;

		document.getElementById(id).addEventListener("touchstart", function(event) {
			scrollStartPosY=this.scrollTop+event.touches[0].pageY;
		//scrollStartPosX=this.scrollLeft+event.touches[0].pageX;
		//event.preventDefault();
		},false);

		document.getElementById(id).addEventListener("touchmove", function(event) {
			if ((this.scrollTop < this.scrollHeight-this.offsetHeight &&
				this.scrollTop+event.touches[0].pageY < scrollStartPosY-5) ||
			(this.scrollTop !== 0 && this.scrollTop+event.touches[0].pageY > scrollStartPosY+5)) {
				event.preventDefault();
			}
			//			if ((this.scrollLeft < this.scrollWidth-this.offsetWidth &&
			//				this.scrollLeft+event.touches[0].pageX < scrollStartPosX-500) ||
			//				(this.scrollLeft != 0 && this.scrollLeft+event.touches[0].pageX > scrollStartPosX+5))
			//					event.preventDefault();	
			this.scrollTop=scrollStartPosY-event.touches[0].pageY;
			this.scrollLeft=scrollStartPosX-event.touches[0].pageX;
		},false);
	}
}

function controlToggle(state) {
	if (state == "show"){
		overlayDiv.style.display = "block";
		document.getElementById(sidebarDivID).style.display = "block";
	} else {
		overlayDiv.style.display = "none";
		document.getElementById(sidebarDivID).style.display = "none";
	}
}

//ht
function onhover(){
	overlayDiv.style.display = "block";
	document.getElementById(sidebarDivID).style.display = "block";
}

function createMarker(latlng, index, html, category, icon, src, title, iconURL) {

	var myHtml;
	if ( icon === undefined ) {
		icon = category;
	}
	if (category == "pin") {

		if (markerGroups["pin"]) {

			for (var i = 0; i < markerGroups["pin"].length; i++) {
				markerGroups["pin"][i].setMap(null);
			}
			markerGroups["pin"].length = 0;
		}

		var image = new google.maps.MarkerImage(iconPath + icon,
			new google.maps.Size(mainIconWidth, mainIconHeight),
			new google.maps.Point(0,0),
			new google.maps.Point(mainAnchorPointX, mainAnchorPointY));
	  
		var shadow = new google.maps.MarkerImage( iconPath + category + "-shadow.png",
			new google.maps.Size(mainIconWidth, mainIconHeight),
			new google.maps.Point(0,0),
			new google.maps.Point(mainAnchorPointX, mainAnchorPointY));

		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			shadow: '',
			icon: image
		});
		if(markerGroups === "") {
			markerGroups = new Array();
		}
		if(markerGroups["pin"] === undefined) {
			markerGroups["pin"] = new Array();
		}
		markerGroups["pin"].push(marker);
		var infowindow = new google.maps.InfoWindow({
			content: html
		});
		if (showinfowindow == true){
			controlToggle("hide");			
			google.maps.event.addListener(infowindow, 'closeclick', function() {
				controlToggle("show");
			});
			infowindow.open(map, marker);
		}
		google.maps.event.addListener(marker, 'click', function() {
			//map.panTo(latlng);
			controlToggle("hide");
			google.maps.event.addListener(infowindow, 'closeclick', function() {
				controlToggle("show");
			});
			infowindow.open(map, marker);
		});

	} else {
		if (icon === ""){
			icon = category;
		}
  
  
		if(useGoogleIcon === true){
			if (iconURL === null){ 
				iconURL = iconPath + icon;
			}
			image = new google.maps.MarkerImage( iconURL,
				new google.maps.Size(iconWidth, iconHeight),
				new google.maps.Point(0,0),
				new google.maps.Point(anchorPointX, anchorPointY),
				new google.maps.Size(iconScaleWidth, iconScaleHeight));
		} else {
			image = new google.maps.MarkerImage( iconPath + icon,
				new google.maps.Size(iconWidth, iconHeight),
				new google.maps.Point(0,0),
				new google.maps.Point(anchorPointX, anchorPointY),
				new google.maps.Size(iconScaleWidth, iconScaleHeight));
		}

	}
	if (category == "xml") {
		//xml processing disabled
		xml = "blank";
	} else {

		marker = new google.maps.Marker({
			position: latlng,
			map: map,
			shadow: '',
			icon: image,
			//		animation: google.maps.Animation.DROP,
			title: category
		});
		marker.setTitle(title);
		markerGroups[category].push(marker);
		infowindow = new google.maps.InfoWindow({
			content: html
		});
		google.maps.event.addListener(marker, 'click', function() {   
			if (lastInfoWindow) {
				controlToggle("show");
				lastInfoWindow.close();
			}
			controlToggle("hide");
			google.maps.event.addListener(infowindow, 'closeclick', function() {
				controlToggle("show");
			});
			map.panTo(latlng);
			if (src == "api"){
				strMatch = infowindow.content;
				strResult = strMatch.match(/api:/ig);
				if(strResult == "api:"){
					reference = strMatch.replace("api:","");
					//				console.log("https://maps.googleapis.com/maps/api/place/details/json?reference=" + reference + "&sensor=false&key=AIzaSyB_tVsms-ULE2W1QGg0XFx2VNHoUlHDfCs");
					var JSON = dbPath + "&task=markers.jsonproxy&url=" + encodeURIComponent("https://maps.googleapis.com/maps/api/place/details/json?reference=" + reference + "&sensor=false");
					mapLoading.style.display = "block";
					jQuery.getJSON(JSON, function(data) {
						if (data.result.hasOwnProperty('website')) {
							htmlURL = data.result.website;
						} else {
							htmlURL = data.result.url;
						}
						if (data.result.hasOwnProperty('rating')) {
							ratingVal = data.result.rating;
						} else {
							ratingVal = "no";
						}
						if (data.result.hasOwnProperty('photos')) {
							photo = data.result.photos[0].photo_reference;
							photoHeight = data.result.photos[0].height;
							photoWidth = data.result.photos[0].width;
						} else {
							photo = "no";
							photoHeight = 0;
							photoWidth = 0;
						}
						var resultHTML = createHTML(data.result.name, htmlURL, data.result.formatted_address, "", "", data.result.formatted_phone_number, data.result.geometry.location.lat, data.result.geometry.location.lng, ratingVal, data.result.url, photo, photoHeight, photoWidth);
						infowindow.content = resultHTML;
						iwHandle(infowindow,map,marker,"");
						mapLoading.style.display = "none";
					});
				} else {
					iwHandle(infowindow,map,marker,"");
				}
			} else {
				iwHandle(infowindow,map,marker,"");
			}
		});
	}
}

function createHTML(title, url, address1, address2, website, phone, lat, lng, rating, placesURL, photo, photoHeight, photoWidth) {
	var onClickSVCode = '"' + lat + '","' + lng + '","' + address1 + '","' + address2 + '"';
	var onClickDDCode = '"' + address1 + ' ' + address2 + '"';
	var finalHTML;
	finalHTML = '<div class="gs-localResult gs-result">';
//	finalHTML = finalHTML + '<div class="gs-title"><a target="_blank" class="gs-title" href="' + url + '">' + title + '</a></div>';
	if ( showRating == true && rating !== "no") {
		finalHTML = finalHTML + '<div><a target="_blank" href="' + placesURL + '" class="gs-rating-' + Math.round(rating) + '"></a></div>';
	}
	finalHTML = finalHTML + '<div class="gs-address">';
	finalHTML = finalHTML + '<div class="gs-street gs-addressLine">' + address1 + '</div>';
	if (address2 !== null) {
		finalHTML = finalHTML + '<div class="gs-city gs-addressLine">' + address2 + '</div>';
	}
	finalHTML = finalHTML + '</div>';
	finalHTML = finalHTML + '<div class="gs-phone">Phone: ' + phone + '</div>';
	//Get Image If Exists
	if (showPhotos == true && photo !== "no") {
		if (maxPhotoWidth > photoWidth) {
		//do nothing
		} else {
			photoHeight = ( maxPhotoWidth / photoWidth) * photoHeight;
			photoWidth = maxPhotoWidth;
		}
		finalHTML = finalHTML + '<div class="gs-photo" style="height: ' + photoHeight + 'px; width: ' + photoWidth + 'px" ><a href="' + placesURL + '" target="_blank"><img src="' + dbPath + 'imageproxy.php?size=' + maxPhotoWidth + '&ref=' + photo + ' "/></a></div>';
	}
	finalHTML = finalHTML + "<div class='gs-streetview'><a class='gs-sv-link' onclick='showStreetView(" + onClickSVCode + ")' style='cursor: pointer'>Street View</a><a class='gs-dd-link' onclick='showDirections(" + onClickDDCode + ")' style='cursor: pointer'>Directions</a></div>";
	finalHTML = finalHTML + '</div>';
	return finalHTML;
}

function createXmlHTML(address, title, html, url, lat, lng) {
	var onClickSVCode = '"' + lat + '","' + lng + '","' + address + '"';
	var onClickDDCode = '"' + address + '"';
	var finalHTML;
	finalHTML = '<div class="gs-localResult gs-result">';
	if (url ==""){
		finalHTML = finalHTML + '<div class="gs-title"><span class="gs-title">' + title + '</span></div>';
	}else{
		finalHTML = finalHTML + '<div class="gs-title"><a target="_blank" class="gs-title" href="' + url + '">' + title + '</a></div>';
	}
	finalHTML = finalHTML + '<div class="gs-customHTML">'
	finalHTML = finalHTML + '<div class="gs-address">' + address + '</div>';
	finalHTML = finalHTML + html;
	finalHTML = finalHTML + '</div>';
//	finalHTML = finalHTML + "<div class='gs-streetview'><a class='gs-sv-link' onclick='showStreetView(" + onClickSVCode + ")' style='cursor: pointer'>Street View</a><a class='gs-dd-link' onclick='showDirections(" + onClickDDCode + ")' style='cursor: pointer'>Directions</a></div>";

	finalHTML = finalHTML + "<div clear='both'></div>";
	finalHTML = finalHTML + "<div class='gs-streetview'><span class='gs-dd-link' style='cursor: pointer'>Directions</span><br/></div>";
	finalHTML = finalHTML + "<div><span class='dirsmall' style='cursor: pointer'>From Address:</span><br/></div>";
	//JS Direction
	var userAddress = '';
	if (useGeolocation === true && userFormattedAddress != null){
		userAddress = userFormattedAddress;
	}

	var fromAddrID = '"' + lat + '_' + lng + '"';
	
	finalHTML = finalHTML +	"<form  id='geDirectionForm" + lat + '_' + lng + "' action='#' onsubmit='showDirectionFromAddr(" + fromAddrID + " , " + onClickDDCode + "); return false' style='margin:0px'>";
	finalHTML = finalHTML + "<input class='inputAddr' type='text' id=" + fromAddrID + " value='" + userAddress + "'/>";
	finalHTML = finalHTML + "<input class='searchButton' type='submit' value='Go' /> </form>";
	
	finalHTML = finalHTML + "<div><span class='dirsmall' style='cursor: pointer'>(e.g. 5th Avenue, New York, NY)</span><br/></div>";
	finalHTML = finalHTML + "<div><a class='gs-sv-link' onclick='showStreetView(" + onClickSVCode + ")' style='cursor: pointer'>Street View</a></div>";
	finalHTML = finalHTML + '</div>';
	return finalHTML;
}

function downloadScript(url) {
	var script = document.createElement('script');
	script.src = url;
	document.body.appendChild(script);
}

function html_entity_decode(str) {
	var ta=document.createElement("textarea");
	ta.innerHTML=str.replace(/</g,"&lt;").replace(/>/g,"&gt;");
	return ta.value;
}

function doSearch(cat_icon, cat_id) {
	mapLoading.style.display = "block";
	var type = cat_id.substr(7);
	currentCategory = type;
	var default_icon = cat_icon.substr(5);
	var icon;
  
	if (markerGroups[type]) {

		for (var i = 0; i < markerGroups[type].length; i++) {
			markerGroups[type][i].setMap(null);
		}
		markerGroups[type].length = 0;
	}

	var hider = document.getElementById(cat_id).getAttribute("caption");
	if (cat_id.substr(0,6) == "poicat"){ 
		var bounds = map.getBounds();
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		var swLat = southWest.lat();
		var swLng = southWest.lng();
		var neLat = northEast.lat();
		var neLng = northEast.lng();
		if (hider != "hidden") {
			var filename = dbPath + "&task=markers.markers&cat="+ type + "&swLat="+ swLat + "&swLng="+ swLng + "&neLat="+ neLat + "&neLng="+ neLng + "&extendLat="+ extendLat + "&extendLng="+ extendLng;
			jQuery.getJSON(filename, function(data) {
				if(data !== null) {
					for( i = 0; i < data.results.length; i++) {
						var result = data.results[i].geometry.location;
						if(result.icon === "") {
							icon = default_icon;
						} else {
							icon = result.icon;
						}
						cleanHTML = html_entity_decode(result.html);
						var xmlHTML = createXmlHTML(result.address, result.name, cleanHTML, result.url, result.lat, result.lng);
						var latlng = new google.maps.LatLng(parseFloat(result.lat), parseFloat(result.lng));
						createMarker(latlng, i, xmlHTML, cat_id, icon, "db", result.name);
					}
				}

				mapLoading.style.display = "none";
			});
		}
	} else {
		if (type == "user") {
			var userName = document.getElementById(cat_id).getAttribute("name");
			if (userName === null) {
				hider = "hidden";
			} else {
				cat_icon = "establishment";
				searchName = userName;
			}
		}
		if (hider != "hidden") {
			var searchName = document.getElementById(cat_id).getAttribute("name");
			if (searchName === null){
				searchName = "";
			} else {
				searchName = "&name=" + searchName.replace(/ /gi, "/");
			}
			var ctr = map.getCenter();
			//alert("Center: " + ctr)
			var jsonLAT = ctr.lat();
			var jsonLNG = ctr.lng();
			if (autoRadius === true){
				searchRadius = distance( map.getBounds().getNorthEast().lat(), map.getBounds().getNorthEast().lng(), map.getBounds().getSouthWest().lat(), map.getBounds().getSouthWest().lng());
			}
			var JSON = dbPath + "&task=markers.jsonproxy&url=" + encodeURIComponent("https://maps.googleapis.com/maps/api/place/search/json?location=" + jsonLAT + "," + jsonLNG + "&radius=" + searchRadius + "&types=" + type + searchName + "&sensor=false");
			jQuery.getJSON(JSON, function(data) {
				classAdder[cat_id] = document.getElementById(cat_id);
				pAddClass(classAdder[cat_id], "zeroResults");
				if(data.status == "ZERO_RESULTS") {
					//alert("No Locations Found: " + document.getElementById(cat_id).getAttribute("name") );
					//classAdder.attributes.getNamedItem("caption").value = "hidden";
					createMarker(latlng, 0, "", cat_id, type, "api", "",iconPath + "images/js_poi/icons/blank.png");
					mapLoading.style.display = "none";
				} else {
					pRemoveClass(classAdder[cat_id], "zeroResults");
					for (i = 0; i < data.results.length; i++) {
						var result = data.results[i];
						var latlng = new google.maps.LatLng(parseFloat(result.geometry.location.lat), parseFloat(result.geometry.location.lng));
						var resultHTML = "api:" + result.reference;
						createMarker(latlng, i, resultHTML, cat_id, type, "api", result.name, result.icon);
						if (hider == "hidden") {
							markerGroups[type][i].hide();
						}
					}
				}
				mapLoading.style.display = "none";
			});
		}
	}
	return 1;
}

function pHasClass(ele, cls) {
	return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

function pAddClass(ele, cls) {
	if (!this.pHasClass(ele, cls)) {
		ele.className += " " + cls;
	}
}

function pRemoveClass(ele, cls) {
	if (pHasClass(ele, cls)) {
		var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
		ele.className = ele.className.replace(reg, ' ');
	}
}

function toggleGroup(type) {
	classAdder = document.getElementById(type);
	if (markerGroups[type].length !== 0) {
		for (var i = 0; i < markerGroups[type].length; i++) {
			var marker = markerGroups[type][i];
			if (marker.getVisible() === false) {
				classAdder.attributes.getNamedItem("caption").value = "";
				pAddClass(classAdder, "visibleLayer");
				marker.setVisible(true);
			} else {
				classAdder.attributes.getNamedItem("caption").value = "hidden";
				pRemoveClass(classAdder, "visibleLayer");
				marker.setVisible(false);
			}
		}
	} else {
		classAdder.attributes.getNamedItem("caption").value = "";
		pAddClass(classAdder, "visibleLayer");
		doSearch(classAdder.attributes.getNamedItem("tabindex").value, type);
	}
}

function handleDDErrors() {
	var ddError = "<h4>Unable to retreive driving directions to this location.</h4><a onclick='closeDirections();' style='text-decoration: underline; cursor: pointer; color: blue'>close</a>";
	var ddErrorDiv = document.createElement('div');
	ddErrorDiv.setAttribute('id', 'ddError');
	ddBoxDiv.appendChild(ddErrorDiv);
	ddErrorDiv.innerHTML = ddError;
	ddErrorDiv.style.width = "50%";
	ddErrorDiv.style.marginLeft = "10%";
}


function closeDirections() {
	mapDiv.removeChild(document.getElementById("ddFrame"));
}

function showDirectionFromAddr(fromAddr, toAddr) {
	fromAddrInput = document.getElementById(fromAddr);
	address = fromAddrInput.value;
	if(address == "") {
		return false;
	}
	
	var markerHTML = "";
	if(useGeolocation === true && address == userFormattedAddress) {
		if(startAddress == userStartAddress) {
			showDirections(toAddr);
			return true;
		} else {
			address = userStartAddress;
			markerHTML = defaultHTML;
		}
	} else {
		markerHTML = "<strong>" + address + "</strong>";
	}	

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( {
		'address': address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			addressSet = 1;
			startAddress = address;
			searchCenter = results[0].geometry.location;
			createMarker(searchCenter, 0, markerHTML, "pin", pinIcon, "");
			getCategories(0);
			if (mapExtra === true) {
				mapPost();
			}
			
			showDirections(toAddr);
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
}

function showDirections(toAddress) {
	var ddFrame = document.createElement('div');
	ddFrame.setAttribute('id', 'ddFrame');
	mapDiv.appendChild(ddFrame);
	centerBox("ddFrame", mapDivID);
	ddBoxDiv = document.createElement('div');
	ddBoxDiv.setAttribute('id', 'ddBox');
	ddFrame.appendChild(ddBoxDiv);
	ddBoxDiv.style.position = "absolute";
	ddBoxDiv.style.left = "5px";
	var ddBoxClose = document.createElement('a');
	ddBoxClose.setAttribute('id', 'ddBoxClose');
	ddFrame.appendChild(ddBoxClose);
	ddBoxClose.style.position = "absolute";
	ddBoxClose.style.zIndex = "10";
	ddBoxClose.style.top = "0px";
	ddBoxClose.style.left = (ddFrame.offsetWidth - ddBoxClose.offsetWidth - 4) + "px";
	ddBoxClose.onclick = function() { 
		closeDirections();
	};
	var ddBoxPrint = document.createElement('a');
	ddBoxPrint.setAttribute('id', 'ddBoxPrint');
	ddFrame.appendChild(ddBoxPrint);
	ddBoxPrint.innerHTML = "<span>Print</span>";
	ddBoxPrint.style.position = "absolute";
	ddBoxPrint.style.zIndex = "10";
	ddBoxPrint.style.top = "4px";
	ddBoxPrint.style.left = (ddFrame.offsetWidth - ddBoxClose.offsetWidth - 29) + "px";
	ddBoxPrint.setAttribute("href", iconPath + "components/com_js_poi/templates/default/assets/print/print.html?start=" + escape(startAddress) + "&end=" + escape(toAddress));
	ddBoxPrint.setAttribute("target", "_blank");
	directionsDisplay.setMap(null);
	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(ddBoxDiv);

	var loadStr = "from: " + startAddress + " to: " + toAddress;

	var request = {
		origin: startAddress, 
		destination: toAddress,
		travelMode: travMode
	};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
			return false;
		} else {
			handleDDErrors();  
			return false;
		}
	});
}

function closeStreetView() {
	controlToggle("hide");
	mapDiv.removeChild(document.getElementById("svFrame"));
	map.setStreetView(null);
	map.getStreetView().setVisible(false);
	controlToggle("hide");
}

function showStreetView(lat, lng, address1, address2) {
	var svFrame = document.createElement('div');
	svFrame.setAttribute('id', 'svFrame');
	mapDiv.appendChild(svFrame);
	centerBox("svFrame", mapDivID);
	var svBoxDiv = document.createElement('div');
	svBoxDiv.setAttribute('id', 'svBox');
	svFrame.appendChild(svBoxDiv);
	svBoxDiv.style.position = "absolute";
	var svBoxClose = document.createElement('a');
	svBoxClose.setAttribute('id', 'svBoxClose');
	svFrame.appendChild(svBoxClose);
	svBoxClose.style.position = "absolute";
	svBoxClose.style.zIndex = "10";
	svBoxClose.style.top = "0px";
	svBoxClose.style.left = (svFrame.offsetWidth - svBoxClose.offsetWidth - 5) + "px";
	svBoxClose.onclick = function() { 
		closeStreetView();
	};
	var svLatLng = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
	var panoramaOptions = {
		position: svLatLng
	};
	var panorama = new  google.maps.StreetViewPanorama(document.getElementById("svBox"), panoramaOptions);
	map.setStreetView(panorama);
	
	var streetViewAvailable;
	var streetViewCheck = new google.maps.StreetViewService();  
	
	streetViewCheck.getPanoramaByLocation(svLatLng, 50, function(result, status) {
		if (status == "OK") {
			streetViewAvailable = 0;        
		}else{
			closeStreetView();
			alert("Street View unavailable for this location");
		}
		controlToggle("hide");
	});

}


function toggleOverlay(layerState, layer, control) {
	if (overlayControls === true) {	
		var toggleControl = document.getElementById(control);
		if (layerState == "off") {
			if (layer.isHidden() === true) {
				layer.show();
			} else {
				map.addOverlay(layer);
			}
			toggleControl.style.backgroundColor = "#fc9";
			toggleControl.onclick = function() {
				toggleOverlay('on', layer , control );
			};
		}
		if (layerState == "on") {
			layer.hide();
			toggleControl.style.backgroundColor = "#fff";
			toggleControl.onclick = function() {
				toggleOverlay('off', layer , control );
			};
		}
	}
}


function getCategories(initial) {
	var i;
	var elem = document.getElementById(categoriesList);
	if (initial == 1) {
		jsonGroups = "";
		jsonGroups = '{ xml: [], "pin": [] ';
		for (i = 0; i < elem.childNodes.length; i++) {
			if (elem.childNodes[i].nodeName == "LI") {
				jsonGroups = jsonGroups + ',  "' + elem.childNodes[i].attributes.getNamedItem("id").value + '": [] ';
			}
		}
		jsonGroups = jsonGroups + "}";
		if(markerGroups !== "" && markerGroups["pin"] !== undefined) {
			markerGroupsPin = markerGroups["pin"];
			markerGroups = eval('(' + jsonGroups + ')');
			for (var i = 0; i < markerGroupsPin.length; i++) {
				markerGroups["pin"].push(markerGroupsPin[i]);
			}
		} else {
			markerGroups = eval('(' + jsonGroups + ')');
		}

		for (i = 0; i < elem.childNodes.length; i++) {
			if (elem.childNodes[i].nodeName == "LI") {
				var elemID = elem.childNodes[i].attributes.getNamedItem("id").value;
				//var elem_icon = elem.childNodes[i].attributes.getNamedItem("title").value;
				//elem_icon = elem_icon.substr(5);
				if (elemID != "user") {
				//elem.childNodes[i].innerHTML = "<a onclick='" + 'toggleGroup("' + elemID + '")' + "' style='background: url(" + iconPath + elem_icon + ") 0px -1px no-repeat;' >" + elem.childNodes[i].innerHTML + "</a>";
					elem.childNodes[i].innerHTML = "<a onclick='" + 'toggleGroup("' + elemID + '")' + "'>" + elem.childNodes[i].innerHTML + "</a>";
				} else {
					elem.childNodes[i].innerHTML = '<form id="userPOIForm" action="#" onsubmit="userPOIFind(this.userPOI.value); return false"><input id="userPOITxt" size="20" name="userPOI" placeholder="' + elem.childNodes[i].innerHTML + '" type="text"><input id="userPOIButton" value="Go" type="submit"> </form>';					
				}				
				if (pHasClass(elem.childNodes[i], "hidden") !== null) {
					elem.childNodes[i].setAttribute("caption", "hidden");
				} else {
					elem.childNodes[i].setAttribute("caption", "");
				}
				if (elem.childNodes[i].attributes.getNamedItem("caption").value != "hidden") {
					classAdder = document.getElementById(elemID);
					pAddClass(classAdder, "visibleLayer");
				}
			}
		}
	}
	for (i = 0; i < elem.childNodes.length; i++) {
		if (elem.childNodes[i].nodeName == "LI") {
			var catType = elem.childNodes[i].attributes.getNamedItem("id").value;
			result = doSearch(elem.childNodes[i].attributes.getNamedItem("tabindex").value, elem.childNodes[i].attributes.getNamedItem("id").value);
		}
	}

	///
	if (initial == 3) {

	}
}
function distance(lat1,lon1,lat2,lon2) {
	var R = 6371; // km (change this constant to get miles)
	var dLat = (lat2-lat1) * Math.PI / 180;
	var dLon = (lon2-lon1) * Math.PI / 180;
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
	Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
	Math.sin(dLon/2) * Math.sin(dLon/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c;
	if (d>1) {
		return Math.round(d) * 1000;
	} else if (d<=1){
		return Math.round(d*1000);
	} else { 
		return d;
	}
}

function userPOIFind(searchText) {
	document.getElementById("user").setAttribute("name", searchText);
	getCategories(0);
}
function findAddress(address, HTML) {
	if (HTML === undefined) {
		HTML = "<strong>" + address + "</strong>";
	}
	markerHTML = HTML;
  
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( {
		'address': address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			addressSet = 1;
			startAddress = address;
			searchCenter = results[0].geometry.location;
			createMarker(searchCenter, 0, markerHTML, "pin", pinIcon, "");
			getCategories(0);
			if (mapExtra === true) {
				mapPost();
			}

		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
	
}

function OnLoad() {
	setupAddress();
	mapDiv = document.getElementById(mapDivID);
	var myLatlng = new google.maps.LatLng(0,0);
	var myOptions = {
		zoom: zoomLevel,
		scrollwheel: true,
		disableDoubleClickZoom: true,
		center: myLatlng,
		mapTypeControl: configmapTypeControl,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			position: google.maps.ControlPosition.BOTTOM_LEFT
		},
		zoomControl: configzoomControl,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.LEFT_TOP
		},
		streetViewControl: configstreetViewControl,
		panControl: false,
		mapTypeId: MapType,
		overviewMapControl: true,
		overviewMapControlOptions: {
			opened: false
		}
		
	};
	map = new google.maps.Map(mapDiv, myOptions);

	if(directionsMode == 'DRIVING') {
		travMode = google.maps.DirectionsTravelMode.DRIVING;
	} else {
		travMode = google.maps.DirectionsTravelMode.WALKING;
	}
	//streetview stuff
	var tempPanorama = map.getStreetView();

	google.maps.event.addListener(tempPanorama, 'visible_changed', function() {
		if (tempPanorama.getVisible()) {
			controlToggle("hide");
		} else {
			controlToggle("show");
		}
	});
	
	infoBox();

	var geocoder = new google.maps.Geocoder();
	var address = startAddress;
	geocoder.geocode( {
		'address': address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			addressSet = 1;
			searchCenter = results[0].geometry.location;
			getCategories(1);
			createMarker(searchCenter, 0, defaultHTML, "pin", pinIcon,"");
			//		var trafficLayer = new google.maps.TrafficLayer();
			//		trafficLayer.setMap(map);
			google.maps.event.addListener(map, "dragend", function () {
				controlToggle("show");
				getCategories(0);
			});
			mobileScroll(sidebarDivID);
			if (autoGeolocation === true) {
				geotarget();
			}
			if (mapExtra === true) {
				mapPost();
			}
		} else {
			alert("Geocode was not successful for the following reason: " + status);
		}
	});
		
}
jQuery(document).ready(function() {
	OnLoad();
	jQuery("#"+sidebarDivID).hover(function(){ 
		togglelist();
	});
});	
