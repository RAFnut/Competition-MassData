"use strict";

var O = {
	version: "Orchestra.js v0.1",
	sendAjaxRequest: function(endpoint, data, callbackSuccess, callbackError){
	 	$.ajax({
	            type: "POST",
	            url: endpoint,
	            contentType: "application/json",
	            dataType: "json",
	            data: data,
	            success: function(data) {
	            	callbackSuccess(data);
	            },
	            error: function(data){
	            	callbackError(data);
	            }
	        });
	},
	l: function(data){
		console.log(data);
	},
	/*Drag and drop features*/
	allowDrop: function(event) {
    	event.preventDefault();
	},
	drag: function(event, callbackAction) {
    	event.dataTransfer.setData("text", event.target.id);

    	if (callbackAction != undefined){
	    	callbackAction();
	    }
	},
	drop: function(event, callbackAction) {
	    event.preventDefault();
	    var data = event.dataTransfer.getData("text");
	    var location = event.target.id;
	    $("#"+location).append($("#"+data));

	    if (callbackAction != undefined){
	    	callbackAction();
	    }
	},
	/*DOM Manipulation*/
	newImage: function(source, newid, newclass, newalt){
		var imageElement = "<img src='" + source + "' ";
		if (newid != undefined) imageElement = imageElement + "id='" + newid + "' ";
		if (newclass != undefined) imageElement = imageElement + "class='" + newclass + "' ";
		if (newalt != undefined) imageElement = imageElement + "alt='" + newalt + "' ";
		imageElement = imageElement + ">";
		return imageElement;
	},
	newText: function(text, newclass){
		var textElement = "";
		if (newclass === undefined)  textElement = "<p>";
		else textElement = "<p class='"+newclass+"'>";
		textElement = textElement + text + "</p>";
		return textElement;
	},
	newHeading: function(text, type, newid, newclass){
		var headingElement = "<h" + type;
		if (newid != undefined) headingElement = headingElement + " id='" + newid + "'";
		if (newclass != undefined) headingElement = headingElement + " class='" + headingElement + "' ";
		headingElement = headingElement + ">" + text + "</h"+type+">";
		return headingElement;
	},
	/*Map functions*/
	addToMap: function(data){
			var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                mapTypeId: 'roadmap'
            };

            map = new google.maps.Map(document.getElementById("GlavnaMapa"), mapOptions);
            map.setTilt(45);

            var infoWindow = new google.maps.InfoWindow(), marker, i;

            for( i = 0; i < data.length; i++ ) {
            var position = new google.maps.LatLng(data[i].lat,data[i].lng);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: "Marker"
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    var content;
                    var slika;
                    content = "Content "+i;
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                }
            })(marker, i));
            map.fitBounds(bounds);    
        }
            
	}
}


/* Callback testers for sendAjaxRequest */
O.tester1 = function (data){
	O.l(data);
}
O.tester2 = function(data){
	O.l(data);
}


/* Sample klase za usere */
var RegisterUser = function(email, username, password){
	this.email = email;
	this.username = username;
	this.password = password;
}

var LoginUser = function(username, password){
	this.username = username;
	this.password = password;
}

var CurrentUser = function(username, mail, photo){
	this.username = username;
	this.mail = mail;
	this.photo = photo;
}


