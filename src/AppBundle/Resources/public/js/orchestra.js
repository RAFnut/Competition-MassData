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
	}
}

O.tester1 = function (data){
	console.log(data);
}
O.tester2 = function(data){
	console.log(data);
}

var RegisterUser = function(email, username, password){
	this.email = email;
	this.username = username;
	this.password = password;
}

var LoginUser = function(username, password){
	this.username = username;
	this.password = password;
}