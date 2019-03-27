ndmApp.factory('userService', function($http,$q,$window) { 
	
	var apiRouter = {
		getProfile: getProfile
	};
	return apiRouter;
	 	 
	function getProfile() {
		return $http({
			method: 'get',
			url: rootURL + '/getEmployeeList/',
			headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
		})
	} 
	 
});