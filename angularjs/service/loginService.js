ndmApp.factory('loginService', function($http,$q,$window) { 
	
	 var apiRouter = {
		loginProcess: loginProcess,
	 };
	 return apiRouter;
	 	 
	function loginProcess(username,password) {
		return $http({
			method: 'post',
			url: rootURL + '/login/',
			data: $.param({
				username: username,
				password: password
			}),
			headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
		})
	 } 
	 
});