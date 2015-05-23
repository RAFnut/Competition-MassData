var map = new google.maps.Map(document.getElementById('SelectMap'), {
    zoom: 12,
    center: new google.maps.LatLng(44.81509654510311, 20.454320323333718),
    mapTypeId: google.maps.MapTypeId.ROADMAP
});

var myMarker = new google.maps.Marker({
    position: new google.maps.LatLng(44.81509654510311, 20.454320323333718),
    draggable: true
});

var circle = new google.maps.Circle({
  map: map,
  radius: 16093,    // 10 miles in metres
  fillColor: '#AA0000'
});
circle.bindTo('center', myMarker , 'position');

google.maps.event.addListener(myMarker, 'dragend', function(evt){
    //console.log(evt.latLng.lat() + ' ----------- ' + evt.latLng.lng());
    $("#LocationLong").val(evt.latLng.lng());
    $("#LocationLati").val(evt.latLng.lat());

});

google.maps.event.addListener(myMarker, 'dragstart', function(evt){
    
});


map.setCenter(myMarker.position);
myMarker.setMap(map);

$("#Radius").change(function(){
    var radiusnew = $("#Radius").val();
    circle.setRadius( radiusnew * 1000);

});