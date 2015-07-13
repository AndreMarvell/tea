	
function initialize() {

  // Create an array of styles.
var styles   = [
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                      { "saturation": -100 },
                      { "lightness": -8 },
                      { "gamma": 1.18 }
                    ]
                }, {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                      { "saturation": -100 },
                      { "gamma": 1 },
                      { "lightness": -24 }
                    ]
                }, {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "administrative",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "transit",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "water",
                    "elementType": "geometry.fill",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "road",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "administrative",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "landscape",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                    "featureType": "poi",
                    "stylers": [
                      { "saturation": -100 }
                    ]
                }, {
                }
                          ];

  // Create a new StyledMapType object, passing it the array of styles,
  // as well as the name to be displayed on the map type control.
  var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});
    
  var myLatLng = new google.maps.LatLng(48.3,4.0833 );

  // Create a map object, and include the MapTypeId to add
  // to the map type control.
  var mapOptions = {
    zoom: 15,
    center: myLatLng,
    scrollwheel: false,
     disableDefaultUI: true,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    }
  };
  var map = new google.maps.Map(document.getElementById('mapCanvas'),
    mapOptions);
    
  var image = '/bundles/teacampuscommon/images/marker.png';

  var myMarker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      icon: image
  });

  //Associate the styled map with the MapTypeId and set it to display.
  map.mapTypes.set('map_style', styledMap);
  map.setMapTypeId('map_style');
}	
	
google.maps.event.addDomListener(window, 'load', initialize);

