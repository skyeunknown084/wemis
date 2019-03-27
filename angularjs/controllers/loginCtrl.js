ndmApp.controller('loginCtrl', function($scope, $window, loginService) {

	$('#username').keypress(function(e){
	    if(e.which == 13){//Enter key pressed
	        $scope.login();
	    }
	});
	$('#password').keypress(function(e){
	    if(e.which == 13){//Enter key pressed
	        $scope.login();
	    }
	});
	$scope.loadingloader = false;
	$scope.login = function() {
		if($scope.username == undefined || $scope.password == undefined){
		    alert('Please complete your login info!');
		}else{
			$scope.loadingloader = true;
			$('#loginloader').show();
			loginService.loginProcess($scope.username,$scope.password)
				.then(function(data){
					if (typeof(Storage) !== "undefined") {
					    // alert('Code for localStorage/sessionStorage.');
					} else {
					    // alert('Sorry! No Web Storage support..');
					}
					if(data.data[0].status == 'success'){
					    localStorage.username = $scope.username;
					    localStorage.usertype = data.data[0].usertype;
					    $window.location.href = indexURL;
					}else if (data.data[0].status == 'failed'){
					    alert('Invalid login info.');
					    $('#loginloader').hide();
					}else if (data.data[0].status == 'sql error'){
					    alert('Query Error.');
					    $('#loginloader').hide();
					}
				
				});
		}
	};
});