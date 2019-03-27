ndmApp.factory('adminService', function($http,$q,$window) { 
	
	 var apiRouter = {
		signupProcess: signupProcess
	 };
	 return apiRouter;

	function signupProcess(fname,lname,email,username,password,usertype) {
		return $http({
			method: 'post',
			url: rootURL + '/signup/',
			data: $.param({
				fname: fname,
                lname: lname,
                email: email,
                username: username,
                password: password,
                usertype: usertype
			}),
			headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
		})
	} 

	 
});