	
function initialize() {

  // Create an array of styles.
  var styles = [
    {
      stylers: [
        { hue: "#0080ff" },
        { saturation: -55 }
      ]
    },{
      featureType: "road",
      elementType: "geometry",
      stylers: [
        { lightness: 100 },
        { visibility: "simplified" }
      ]
    },{
      featureType: "poi",
      elementType: "all",
      stylers: [
        { lightness: 100 },
        { visibility: "simplified" }
      ]
    },{
      featureType: "road",
      elementType: "labels",
      stylers: [
        { visibility: "off" }
      ]
    }
  ];

  // Create a new StyledMapType object, passing it the array of styles,
  // as well as the name to be displayed on the map type control.
  var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});
    
  var myLatLng = new google.maps.LatLng(55.743635, 37.624201);

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
    
  var image = 'assets/images/marker.png';

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

