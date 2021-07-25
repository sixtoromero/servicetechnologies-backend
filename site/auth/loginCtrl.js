var app = angular.module('loginApp',['ngRoute']);
app.controller('loginCtrl', ['$scope','$http', function($scope,$http){

    $scope.urlAPI = 'https://servicestenhnologies.herokuapp.com/public/index.php/auth/';

	$scope.user = {};        
    $scope.isCreateUser = false;
    $scope.user.usertype = 'Client';        

    $scope.getLogin = function() {        
        $http.post('https://servicestenhnologies.herokuapp.com/public/index.php/auth/login', $scope.user).success(function(data){
            //console.log(data);
            if (data['status'] === 200){                
                localStorage.setItem('user', JSON.stringify(data['data']));
                window.location = "/site/index.html";
            } else {
                localStorage.clear();
                swal("Orders!", data.message, "info");
            }
        });
    }
    
    $scope.registerUser = function() {        
        $http.post('https://servicestenhnologies.herokuapp.com/public/index.php/api/users/create', $scope.user).success(function(data){            
            if (data['status'] === 201){
                $scope.user = {};
                $scope.user.usertype = 'Client';
                swal('Orders!', data.message, "success");
            } else {
                localStorage.clear();
                swal("Orders!", data.message, "info");
            }
        });
        
    }

}]);