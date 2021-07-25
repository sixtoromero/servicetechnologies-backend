app.controller('informesCtrl', ['$scope', '$http', function($scope, $http){
    
    $scope.setActive("mUsers");

    $scope.urlAPI = 'https://servicestenhnologies.herokuapp.com/public/index.php/api/';

    $scope.listClients = {};
    $scope.listOrders = {};
    $scope.orders = {};
    $scope.payments = {};
    $scope.userId = 0;
    $scope.orderId = 0;
    $scope.order_Id = '';
    $scope.isPayment = false;    

    $scope.allClient = function(){                

        $http.get($scope.urlAPI + 'users').success(function(data){
		    if (data.status == 200)  {
                $scope.listClients = data.data;
            }else {
                alert('Ha ocurrido un error');
            }
	    });
    }

    $scope.ordresByUserId = function(IdUser){                
                
        $scope.listOrders = {};
        $scope.orders = {};
        $scope.payments = {};
        $scope.isPayment = false;

        $http.get($scope.urlAPI + 'orders/FindByUserId/' + IdUser).success(function(data){
		    if (data.status == 200)  {                
                $scope.listOrders = data.data;                
            }else {
                $scope.listOrders = {};
            }
	    });
    }

    $scope.generateReportbyUser = function() {
        
        $scope.isPayment = false;

        if ($scope.userId == 0) {            
            $http.get($scope.urlAPI + 'orders/FindOrderAll').success(function(data){
                if (data.status == 200)  {
                    $scope.orders = data.data;
                }else {
                    $scope.orders = {};
                }
            });
        } else {
            if ($scope.orderId == 0){
                $http.get($scope.urlAPI + 'orders/FindByUserIdAndOrders/' + $scope.userId).success(function(data){
                    if (data.status == 200)  {                
                        $scope.orders = data.data;
                    }else {
                        $scope.listOrders = {};
                    }
                });
            } else {
                $http.get($scope.urlAPI + 'orders/FindByOrderId/' + $scope.orderId).success(function(data){
                    if (data.status == 200)  {                
                        $scope.orders = data.data;
                    }else {
                        $scope.listOrders = {};
                    }
                });
            }
        }
        
    }

    $scope.viewPayments = function(invoice_id, order_id) {
        $scope.order_Id = order_id;
        $http.get($scope.urlAPI + 'payments/FindById/' + invoice_id).success(function(data){
            if (data.status == 200)  {
                //$scope.orders = data.data;
                $scope.payments = data.data;
                $scope.isPayment = true;
            }else {
                $scope.isPayment = false;
                $scope.payments = {};
                alert('The Order has no assigned payments');
            }
        });
    }    

}]);