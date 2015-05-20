var rafatonApp = angular.module('rafatonApp', ['ngRoute']);


rafatonApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
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


stocksApp.controller('RegisterController', ["$scope", function ($scope) {

}]);

stocksApp.controller('LoginController', ["$scope", function ($scope) {

}]);

