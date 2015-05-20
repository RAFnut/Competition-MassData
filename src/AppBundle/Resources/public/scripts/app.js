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

rafatonApp.controller('RegisterController', ["$scope",'$http', function ($scope, $http) {

        $scope.newUser = {};
        $( "#RegisterForm" ).submit(function( event ) {
            $scope.newUser = {
                username: $( "#InputUsername" ).val(), 
                password: $( "#InputPassword" ).val()
            };
            console.log($scope.newUser);
            $http.post(Routing.generate('register'), $scope.newUser).
                success(function(data, status, headers, config) {
                    console.log(data);
                }).
                error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                });
            
            event.preventDefault();
        });   

}]);

rafatonApp.controller('LoginController', ["$scope", function ($scope) {

}]);


