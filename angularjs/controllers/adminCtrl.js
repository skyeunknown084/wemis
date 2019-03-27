ndmApp.controller('adminCtrl', function($scope, $rootScope, adminService) {
	//alert("test");
	//** Cooment here
    $rootScope.usertype = localStorage.usertype;
    $rootScope.loader = true;

    $rootScope.member_info = localStorage.member_info;
    $rootScope.attendance = localStorage.attendance;
    $rootScope.schedule = localStorage.schedule;
    $rootScope.salary = localStorage.salary;
    
    // renewCacheTime();

	$scope.fnameDisplay = '';
	$scope.lnameDisplay = '';
	$scope.unameDisplay = '';
	$scope.emailDisplay = '';

	// adminService.getProfile(localStorage.username)
	// 	.then(function(data){
	// 		// alert(data.data[0].first_name);
	// 		$scope.userID = data.data[0].userID;
	// 		$scope.fnameDisplay = data.data[0].first_name;
	// 		$scope.lnameDisplay = data.data[0].last_name;
	// 		$scope.unameDisplay = data.data[0].username;
	// 		$scope.emailDisplay = data.data[0].email;

 //            //for edit purpose
 //            $scope.fnameEdit = data.data[0].first_name;
 //            $scope.lnameEdit = data.data[0].last_name;
 //            $scope.unameEdit = data.data[0].username;
 //            $scope.emailEdit = data.data[0].email;

	// 		//for edit profile purpose
 //            $scope.usernameOrigFetch = angular.copy($scope.unameEdit);
	// 	});

	// adminService.getUserList()
	// 	.then(function(data){
	// 		$scope.userList = data.data;
 //            $rootScope.loader = false;
	// 	});


    $scope.loadUserRole = function(uID,uname,utype){
        $scope.userIDEdit = uID;
        $scope.usernameEdit = uname;
        $scope.usertypeEdit = utype;
        $scope.usernameOrig = angular.copy($scope.usernameEdit);
        $scope.usertypeOrig = angular.copy($scope.usertypeEdit);
    }

    $scope.saveEditRole = function(uID,uname,utype){
        var username = uname;
        var usertype = utype;
        if(uname==$scope.usernameOrig){
            username = 'same_value';
        }
        if(utype==$scope.usertypeOrig){
            usertype = 'same_value';
        }
        $rootScope.loader = true;
        adminService.saveEditRole(uID,username,usertype)
            .then(function(data){
                alert(data.data);
                if(data.data == "Success"){
                    adminService.getUserList()
                        .then(function(data){
                            $scope.userList = data.data;
                            $rootScope.loader = false;
                        });
                }else{
                    $rootScope.loader = false;
                }
            });

    }

	$scope.signUp = function(){
		// alert($scope.usertype + ":" + $scope.firstname + ":" + $scope.lastname + ":" + $scope.email + ":" + $scope.usernameInput + ":" + $scope.pwd + ":" + $scope.confirmPwd);

		if($scope.usertype == undefined || $scope.firstname == undefined || $scope.lastname == undefined || $scope.email == undefined || $scope.usernameInput == undefined || $scope.pwd == undefined || $scope.usertype == 'none' || $scope.firstname == '' || $scope.lastname == '' || $scope.email == '' || $scope.usernameInput == '' || $scope.pwd == ''){
		    alert('Please complete the form!');
		}else{
            $rootScope.loader = true;
			adminService.signupProcess($scope.firstname,$scope.lastname,$scope.email,$scope.usernameInput,$scope.pwd,$scope.usertype)
				.then(function(data){
					alert(data.data);
                    location.reload();
                    // adminService.getUserList()
                    //     .then(function(data){
                    //         $scope.userList = data.data;
                    //         $rootScope.loader = false;
                    //     });
				});
		}
	}
	
    $scope.fnameDisplay = {
        fnameDisplay: 'first_name',
        password: null
    };

    $scope.open = function () {

        $modal.open({
            templateUrl: 'myModalContent.html',
            backdrop: true,
            windowClass: 'modal',
            controller: function ($scope, $modalInstance, $log, fnameDisplay) {
                $scope.fnameDisplay = fnameDisplay;
                $scope.submit = function () {
                    $log.log('Submiting user info.');
                    $log.log(fnameDisplay);
                    $modalInstance.dismiss('cancel');
                }
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            },
            resolve: {
                fnameDisplay: function () {
                    return $scope.fnameDisplay;
                }
            }
        });
    };

	$scope.user={};
    $scope.saveProfile = function(){
        var username = $scope.unameEdit;
        if(username==$scope.usernameOrigFetch){
            username = 'same_value';
        }
		// alert(username);
        $rootScope.loader = true;
		adminService.saveProfile($scope.userID,$scope.fnameEdit,$scope.lnameEdit,username,$scope.emailEdit)
			.then(function(data){
                $rootScope.loader = false;
                alert(data.data);
                if(data.data == 'Success'){
				    localStorage.username = $scope.unameDisplay;
                    $scope.fnameDisplay = $scope.fnameEdit;
                    $scope.lnameDisplay = $scope.lnameEdit;
                    $scope.unameDisplay = $scope.unameEdit;
                    $scope.emailDisplay = $scope.emailEdit;
                }
            });
    }

    $scope.savePassword = function(){
        $rootScope.loader = true;
        adminService.savePassword($scope.userID,$scope.oldPass,$scope.newPass)
            .then(function(data){
                $rootScope.loader = false;
                alert(data.data);
            }
        );
    }

	// Delete User List
	var deleteArray=[];
	$scope.selectToDel = function(selected,userID){
        if(selected == true){
            if (deleteArray.indexOf(userID) == -1) {
                deleteArray.push(userID);
            }
            console.log(deleteArray);
        }else{
            var index = deleteArray.indexOf(userID);
            if (index > -1) {
                deleteArray.splice(index, 1);
            }
            console.log(deleteArray);
        }
    }
	
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
        if (!$scope.selectedAll) {
            $scope.selectedAll = true;
        } else {
            $scope.selectedAll = false;
        }
        angular.forEach($scope.userList, function(userList) {
            userList.selected = $scope.selectedAll;
            if($scope.selectedAll == true){
                if (deleteArray.indexOf(userList.userID) == -1) {
                    deleteArray.push(userList.userID);
                }
            }else{
                var index = deleteArray.indexOf(userList.userID);
                if (index > -1) {
                    deleteArray.splice(index, 1);
                }
            }
        });
        console.log(deleteArray);
    });    
            
    $('.deleteall').on("click", function(event){
        if(deleteArray.length < 1){ 
            alert("No data selected");
        }else{
            var tb = $(this).attr('title');
            var sel = false;
            var ch = $('#'+tb).find('tbody input[type=checkbox]');
            var c = confirm('Are you sure to delete this item?');
            if(c) {
                $rootScope.loader = true;
                adminService.delUser(deleteArray)
                    .then(function(data){
                        $rootScope.loader = false;
                        alert(data.data);
                        deleteArray=[];
        
                        ch.each(function(){
                           var $this = $(this);
                              if($this.is(':checked')) {
                                      sel = true; //set to true if there is/are selected row
                                  $this.parents('tr').fadeOut(function(){
                                  $this.remove(); //remove row when animation is finished
                                  });
                              }
                        });
                        if(!sel) alert('No data selected');
                    });
              
            }
        }
    return false;
    }); 
		
		
	// Validation Email and Username//
    $scope.usernameInput = '';
    $scope.email = '';
    $scope.pwd = '';
    $scope.confirmPwd = '';
    //login page
    $scope.password ='';
    //change password
    $scope.oldPass = '';
    // $scope.newPwd = '';
    // $scope.newconfirmPwd = '';

		//**User List Pagination 
		  $scope.data = [{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""},{"username":"","usertype":""}];
		  $scope.viewby = "50";
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

//Link Password to Confirm Password
   