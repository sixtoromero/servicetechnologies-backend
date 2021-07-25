var app = angular.module('ordersApp',['ngRoute']);

app.controller('mainCtrl', ['$scope','$http', function($scope, $http){
  
	$scope.menuSuperior = 'pages/menu.html';

	if (localStorage.getItem("user") == null){
		window.location = 'auth/login.html';
	}


	$scope.setActive = function(Opcion){

		$scope.mInicio     = "";
		$scope.mOrders = "";
		$scope.mUsers    = "";

		$scope[Opcion] = "active";

	}

	$scope.logOut = function(){
		let res = confirm('Do you want to log out?');
		if (res){
			localStorage.removeItem('user');
			window.location = 'auth/login.html';
		}
	}

}]);


//Filtro personalizado de telefono
app.filter('telefono', function(){
	return function(numero){
		return numero.substring(0, 4) + "-" + numero.substring(4);
	}
});

// app.directive('numberMask', function() {
//     return {
//         restrict: 'A',
//         link: function(scope, element, attrs) {
//             $(element).numeric();
//         }
//     }
// });
