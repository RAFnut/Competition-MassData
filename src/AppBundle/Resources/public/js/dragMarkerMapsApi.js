var styleArray = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}];

var styledMap = new google.maps.StyledMapType(styleArray, {name: "Styled Map"});

var map = new google.maps.Map(document.getElementById('SelectMap'), {
    zoom: 6,
    center: new google.maps.LatLng(42.930719, -75.254394),
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    }
});

var myMarker = new google.maps.Marker({
    position: new google.maps.LatLng(42.930719, -75.254394),
    draggable: true
});

var circle = new google.maps.Circle({
  map: map,
  radius: 16093,    // 10 miles in metres
  fillColor: '#005791',
  strokeColor: '#005791',
  strokeWeight: 1
});
circle.bindTo('center', myMarker , 'position');

google.maps.event.addListener(myMarker, 'dragend', function(evt){
  
    $("#appbundle_query_lng").val(evt.latLng.lng());
    $("#appbundle_query_lat").val(evt.latLng.lat());

});

google.maps.event.addListener(myMarker, 'dragstart', function(evt){
    
});

map.mapTypes.set('map_style', styledMap);
map.setMapTypeId('map_style');
map.setCenter(myMarker.position);
myMarker.setMap(map);

$("#Radius").change(function(){
    var radiusnew = $("#Radius").val();
    circle.setRadius( radiusnew * 1000);

});