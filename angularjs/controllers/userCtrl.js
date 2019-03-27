ndmApp.controller('userCtrl', function($scope, userService) {

	// alert("test");
	// $scope.fnameDisplay = '';
	// $scope.lnameDisplay = '';
	// $scope.unameDisplay = '';
	// $scope.emailDisplay = '';

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

	$scope.fname = '';
	$scope.lname = '';
	$scope.mname = '';
	$scope.usertype = '';
	$scope.email_address = '';
	$scope.nationality = '';
	$scope.birthday = '';

	userService.getProfile()
	.then(function(data){
		//List of Array Data
		$scope.employeeList = data.data;
		//Data by data in Array
		$scope.emp_id = data.data[0].emp_id;
		$scope.fname = data.data[0].fname;
		$scope.lname = data.data[0].lname;
		$scope.mname = data.data[0].mname;
		$scope.usertype = data.data[0].usertype;
		$scope.email = data.data[0].email;
		$scope.age = data.data[0].age;
		$scope.gender = data.data[0].gender;
		$scope.status = data.data[0].status;
		$scope.citizen = data.data[0].citizen;
		$scope.bday = data.data[0].bday;
		$scope.address = data.data[0].address;
		$scope.sss = data.data[0].sss;
		$scope.tin = data.data[0].tin;
	});

	//Editor's Table List
	$scope.activepage = "mainpage";
	
	$scope.cancelEdit = function(){
		$scope.activepage = 'mainpage';
	}

	$scope.editUser = function(){
		$scope.activepage = 'editpage';

		userService.getProfile()
		.then(function(data){
			//List of Array Data
			$scope.employeeList = data.data;
			//Data by bata Array
			$scope.emp_idEdit = data.data[0].emp_id;
			// $scope.addressDisplay = data.data[0].home_address;
			// $scope.address = data.data[0].home_address;
		});
	}

	$scope.addUser = function(){
		$scope.activepage = 'addpage';

		userService.getProfile()
		.then(function(data){
			//List of Array Data
			$scope.employeeList = data.data;
			//Data by bata Array
			// $scope.emp_idEdit = data.data[0].emp_id;
			// $scope.addressDisplay = data.data[0].home_address;
			// $scope.address = data.data[0].home_address;
		});
	}

// 	$(function () {
//   $('#date').combodate();
// });

// $("button").click(function () {
//   launchChildRegisterModal();
// });
// var launchChildRegisterModal = function () {
//   $('#date').combodate('setValue', "12/12/2003");
// };
});