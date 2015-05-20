var rafatonApp = angular.module('rafatonApp', ['ngRoute']);


rafatonApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
    .when('/',  {
        controller: 'HomeController', 
        templateUrl: 'templates/home.html'
    })

    .when('/register',  {
        controller: 'RegisterController', 
        templateUrl: 'templates/register.html'
    })
    
    .when('/login',{
        controller: 'LoginController', 
        templateUrl: 'templates/register.html' 
    })
    .otherwise({redirectTo:'/' });

}]);

rafatonApp.controller('HomeController', ["$scope", function ($scope) {

}]);

rafatonApp.controller('RegisterController', ["$scope", function ($scope) {

}]);

rafatonApp.controller('LoginController', ["$scope", function ($scope) {

}]);

