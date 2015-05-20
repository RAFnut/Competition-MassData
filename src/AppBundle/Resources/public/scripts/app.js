"use strict";

var rafatonApp = angular.module('rafatonApp', ['ngRoute']);


rafatonApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
    .when('/',  {
        controller: 'HomeController', 
        templateUrl: dirTemplatesPath+'templates/home.html'
    })

    .when('/register',  {
        controller: 'RegisterController', 
        templateUrl: dirTemplatesPath+'templates/register.html'
    })
    
    .when('/login',{
        controller: 'LoginController', 
        templateUrl: dirTemplatesPath+'templates/register.html' 
    })
    .otherwise({redirectTo:'/' });

}]);



rafatonApp.controller('HomeController', ["$scope", function ($scope) {

}]);

rafatonApp.controller('RegisterController', ["$scope", function ($scope) {

        $scope.newUser = {};
        $( "#RegisterForm" ).submit(function( event ) {
            $scope.newUser = {
                username: $( "#InputUsername" ).val(), 
                password: $( "#InputPassword" ).val()
            };
            console.log($scope.newUser);
            event.preventDefault();
        });   

}]);

rafatonApp.controller('LoginController', ["$scope", function ($scope) {

}]);

