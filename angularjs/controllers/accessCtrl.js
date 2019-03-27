ndmApp.controller('accessCtrl', function($scope, $rootScope, adminService) {
	//alert("test");
	//** Cooment here
    $rootScope.usertype = localStorage.usertype;
    $rootScope.loader = true;

    $rootScope.skinproduct_mgt = localStorage.member_info;
    $rootScope.csv_converter = localStorage.attendance;
    $rootScope.EC_link_fetch = localStorage.schedule;
    $rootScope.beautybox_mgt = localStorage.salary;
    renewCacheTime();

	adminService.getAccessList()
		.then(function(data){
			$scope.accessList = data.data;
            $rootScope.loader = false;
            console.log(data.data);
		});


	$scope.updateAccessBtn = function(id){
		alert(id);
	}

	$scope.updclk = function(id,db_index){
		$rootScope.loader = true;
		adminService.updateAccess(id,db_index)
			.then(function(data){
				if(data.data == "Success"){
					$rootScope.loader = false;
				}else{
					alert("Failed");
					$rootScope.loader = false;
				}
			}
		);
	}


		//**User List Pagination 
		  $scope.data = [{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""}];
		  $scope.viewby = 50;
		  $scope.totalItems = $scope.data.length;
		  $scope.currentPage = 1;
		  $scope.itemsPerPage = $scope.viewby;
		  $scope.maxSize = 5; //Number of pager buttons to show

		  $scope.setPage = function (pageNo) {
			$scope.currentPage = pageNo;
		  };

		  $scope.pageChanged = function() {
			console.log('Page changed to: ' + $scope.currentPage);
		  };

		$scope.setItemsPerPage = function(num) {
		  $scope.itemsPerPage = num;
		  $scope.currentPage = 1; //reset to first paghe
		}
   

});
   