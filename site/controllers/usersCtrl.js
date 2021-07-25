app.controller('usersCtrl', ['$scope','$http', function($scope,$http){
		
	$scope.urlAPI = 'https://servicestenhnologies.herokuapp.com/public/index.php/api/';

	$scope.users = {};
	$scope.posicion = 5;

	$http.get($scope.urlAPI + 'users').success(function(data){
        if (data.status === 200){
            console.log(data);
            $scope.users = data.data;
        }		
	});
	
}]);