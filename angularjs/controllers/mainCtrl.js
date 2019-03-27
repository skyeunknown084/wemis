ndmApp.controller('mainCtrl', function($scope,$window, $rootScope, FileUploader, adminService){
    $scope.usertype = localStorage.usertype;
    $rootScope.usertype = localStorage.usertype;
    $rootScope.loader = true;
    $rootScope.url="api/public/blob/upload/";
    $rootScope.url2="api/src/upload.php?username="+localStorage.username;
    $rootScope.url3="api/src/uploadtips.php?username="+localStorage.username;
    $rootScope.url4="api/src/uploademailcontent.php?username="+localStorage.username;
    $rootScope.hasuploaded=false;
    renewCacheTime();
    
    adminService.getCurrentAccess(localStorage.username)
        .then(function(data){
            $rootScope.loader = false;
            localStorage.skinproduct_mgt = data.data[0].skinproduct_mgt;
            localStorage.csv_converter = data.data[0].csv_converter;
            localStorage.EC_link_fetch = data.data[0].EC_link_fetch;
            localStorage.beautybox_mgt = data.data[0].beautybox_mgt;
            localStorage.maintenance_mgt = data.data[0].maintenance_mgt;
            localStorage.skinraw_mgt = data.data[0].skinraw_mgt;
            localStorage.device_mgt = data.data[0].device_mgt;
            localStorage.bodyraw_mgt = data.data[0].bodyraw_mgt;
            localStorage.skinreport_summary_mgt = data.data[0].skinreport_summary_mgt;
            localStorage.tips_mgt = data.data[0].tips_mgt;
            localStorage.member_mgt = data.data[0].member_mgt;
            $rootScope.skinproduct_mgt = data.data[0].skinproduct_mgt;
            $rootScope.csv_converter = data.data[0].csv_converter;
            $rootScope.EC_link_fetch = data.data[0].EC_link_fetch;
            $rootScope.beautybox_mgt = data.data[0].beautybox_mgt;
            $rootScope.maintenance_mgt = data.data[0].maintenance_mgt;
            $rootScope.skinraw_mgt = data.data[0].skinraw_mgt;
            $rootScope.device_mgt = data.data[0].device_mgt;
            $rootScope.bodyraw_mgt = data.data[0].bodyraw_mgt;
            $rootScope.skinreport_summary_mgt = data.data[0].skinreport_summary_mgt;
            $rootScope.tips_mgt = data.data[0].tips_mgt;
            $rootScope.member_mgt = data.data[0].member_mgt;
            $rootScope.history_mgt = localStorage.history_mgt;
        }
    );
	
    adminService.getVersTime()
        .then(function(data){
            $scope.verstime = data;
        });
        
    adminService.getCsvNotif()
        .then(function(data){
            $rootScope.loader = true;
            $scope.csvNotifTotal = data.data;
            $rootScope.loader = false;
        });
        
	adminService.getSCPTotal()
		.then(function(data){
			$scope.totalScPItems = data.data;
		}
	);
	
	adminService.bboxTotal()
        .then(function(data){
            $scope.bboxTotal = data.data;
			//console.log($scope.totalUnArrItems);
    }); 
		
    $scope.Uploaded = function () {
        $rootScope.hasuploaded=true;
    }; 
    $scope.hasUploaded = function () {
        return $rootScope.hasuploaded;
    }; 

    $scope.OpenLink = function (filename) {
    console.log(filename);
    parts = filename.split(".");
    x = parts[0];
        
    $window.open("api/src/displayReport.php?filename="+x+".hst", '_blank');
	 
    };  
    $scope.OpenPhotoUploadResult= function () {
    $window.open("api/src/displayReport.php?filename=uploadPhotoReport.hst", '_blank');

    };  
	 $scope.OpenLink = function (filename) {
    console.log(filename);
    parts = filename.split(".");
    x = parts[0];
        
    $window.open("api/src/displayReport.php?filename="+x+".hst", '_blank');
	 
    };  
    $scope.OpenPhotoUploadResult= function () {
    $window.open("api/src/displayReport.php?filename=uploadPhotoReport.hst", '_blank');

    };  
    $scope.CheckFile = function (filename) {
    console.log(filename);

    parts = filename.split(".");
    x = parts[1];
    if(x=='zip'){
        $rootScope.url="api/src/uploadzip.php?username="+localStorage.username;
       
    }else if(x=='rar'){
        $rootScope.url="api/src/uploadrar.php?username="+localStorage.username;
        
    }else{
       
        $rootScope.url="api/public/blobupload/"+localStorage.username;
    }
        
    // $window.open("api/src/displayReport.php?filename="+x+".txt", '_blank');
    };   
    //CSV TO DB Converter 
    var uploader = $scope.uploader = new FileUploader({
            url: $rootScope.url2
        });

        // FILTERS
      
        // a sync filter
        uploader.filters.push({
            name: 'syncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                console.log('syncFilter');
                return this.queue.length < 10;
            }
        });
      
        // an async filter
        uploader.filters.push({
            name: 'asyncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options, deferred) {
                console.log('asyncFilter');
                setTimeout(deferred.resolve, 1e3);
            }
        });
        var uploadertips = $scope.uploadertips = new FileUploader({
            url: $rootScope.url3
        });

        // FILTERS
      
        // a sync filter
        uploadertips.filters.push({
            name: 'syncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                console.log('syncFilter');
                return this.queue.length < 10;
            }
        });
      
        // an async filter
        uploadertips.filters.push({
            name: 'asyncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options, deferred) {
                console.log('asyncFilter');
                setTimeout(deferred.resolve, 1e3);
            }
        });
        var uploaderemailcontent = $scope.uploaderemailcontent = new FileUploader({
            url: $rootScope.url4
        });

        // FILTERS
      
        // a sync filter
        uploaderemailcontent.filters.push({
            name: 'syncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                console.log('syncFilter');
                return this.queue.length < 20;
            }
        });
      
        // an async filter
        uploaderemailcontent.filters.push({
            name: 'asyncFilter',
            fn: function(item /*{File|FileLikeObject}*/, options, deferred) {
                console.log('asyncFilter');
                setTimeout(deferred.resolve, 1e3);
            }
        });

        //uploader2
    var uploader2 = $scope.uploader2 = new FileUploader({
            url: $rootScope.url
        });

        // FILTERS
      
        // a sync filter
        uploader2.filters.push({
            name: 'syncFilter',
            fn: function(item2 /*{File|FileLikeObject}*/, options) {
                console.log('syncFilter');
                return this.queue.length < 150;
            }
        });
      
        // an async filter
        uploader2.filters.push({
            name: 'asyncFilter',
            fn: function(item2 /*{File|FileLikeObject}*/, options, deferred) {
                console.log('asyncFilter');
                setTimeout(deferred.resolve, 1e3);
            }
        });
        uploader2.onBeforeUploadItem = function(item) {

            filename= item.file.name;
        parts = filename.split(".");
        x = parts[1];
        if(x=='zip'){
            $rootScope.url="api/src/uploadzip.php?username="+localStorage.username;
           
        }else if(x=='rar'){
            $rootScope.url="api/src/uploadrar.php?username="+localStorage.username;
            
        }else{
           
            $rootScope.url="api/public/blobupload/"+localStorage.username;
        }
            item.url = $rootScope.url;
        };


    //uploader3
    var uploader3 = $scope.uploader3 = new FileUploader({
            url: $rootScope.url
        });

        // FILTERS
      
        // a sync filter
        uploader3.filters.push({
            name: 'syncFilter',
            fn: function(item3 /*{File|FileLikeObject}*/, options) {
                console.log('syncFilter');
                return this.queue.length < 50;
            }
        });
      
        // an async filter
        uploader3.filters.push({
            name: 'asyncFilter',
            fn: function(item3 /*{File|FileLikeObject}*/, options, deferred) {
                console.log('asyncFilter');
                setTimeout(deferred.resolve, 1e3);
            }
        });
        uploader3.onBeforeUploadItem = function(item) {

            filename= item.file.name;
        parts = filename.split(".");
        x = parts[1];
        if(x=='zip'){
            $rootScope.url="api/src/uploadzip.php?username="+localStorage.username;
           
        }else if(x=='rar'){
            $rootScope.url="api/src/uploadrar.php?username="+localStorage.username;
            
        }
        // else{
           
        //     $rootScope.url="api/public/blobupload/"+localStorage.username;
        // }
            item.url = $rootScope.url;
        };
    // $scope.saveExcel==false;
    //  // $scope.IsdeleteBar = false;
    //           $scope.saveToDB = function () {
    //             //If DIV is visible it will be hidden and vice versa.
    //             $scope.saveExcel = $scope.saveExcel ? false : true;
    //         }    

    // $scope.myInterval = 0;
    // $scope.slides = [
    //    {
    //      image: 'img/himirror.png'
    //    }
    //  ];

    $scope.percent = 65;
    $scope.anotherPercent = -45;
    $scope.anotherOptions = {
        animate:{
            duration:0,
            enabled:false
        },
        barColor:'#2C3E50',
        scaleColor:false,
        lineWidth:20,
        lineCap:'circle'
    };

    $scope.percent2 = 25;
    $scope.anotherPercent2 = -45;
    $scope.anotherOptions2 = {
        animate:{
            duration:0,
            enabled:false
        },
        barColor:'#2C3E50',
        scaleColor:false,
        lineWidth:20,
        lineCap:'circle'
    };

    $scope.percent3 = 85;
    $scope.anotherPercent3 = -45;
    $scope.anotherOptions3 = {
        animate:{
            duration:0,
            enabled:false
        },
        barColor:'#2C3E50',
        scaleColor:false,
        lineWidth:20,
        lineCap:'circle'
    };
    //--------------
    $scope.colors = ['#45b7cd', '#ff6384', '#ff8e72'];
    $scope.labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $scope.data = [
      [65, -59, 80, 81, -56, 55, -40],
      [28, 48, -40, 19, 86, 27, 90]
    ];
    $scope.datasetOverride = [
      {
        label: "Bar chart",
        borderWidth: 1,
        type: 'bar'
      },
      {
        label: "Line chart",
        borderWidth: 3,
        hoverBackgroundColor: "rgba(255,99,132,0.4)",
        hoverBorderColor: "rgba(255,99,132,1)",
        type: 'line'
      }
    ];
    
       $scope.labels1 = ['2006', '2007', '2008', '2009', '2010', '2011', '2012'];
  $scope.series1 = ['Series A', 'Series B'];

  $scope.data1 = [
    [65, 59, 80, 81, 56, 55, 40],
    [28, 48, 40, 19, 86, 27, 90]
  ];
   $scope.onClick = function (points, evt) {
    console.log(points, evt);
  };

  $scope.datasetOverride1 = [{ yAxisID: 'y-axis-1' }, { yAxisID: 'y-axis-2' }];
  $scope.options1 = {
    scales: {
      yAxes: [
        {
          id: 'y-axis-1',
          type: 'linear',
          display: true,
          position: 'left'
        },
        {
          id: 'y-axis-2',
          type: 'linear',
          display: true,
          position: 'right'
        }
      ]
    }
  };

  $scope.show=1;
  $scope.slide=1;
      adminService.displayReport()
        .then(function(data){
            $scope.rawSCAP = data.data;
			//alert(data.data);
			//console.log($scope.rawSCAP);
            $rootScope.loader = false;
        });


    $scope.uploadAll = function(){
         $rootScope.loader = true;
          //var scapBatchItems = [];
        var scapBatchItemsSave = []
        var scpItemArray = [];
        angular.forEach($scope.rawSCP, function(ids) {
            
            // if (item.checked) 
            //console.log(item);
            scpItemArray.push(ids);
        });
        var scapBatchItemsSave = scpItemArray;
        console.log(scapBatchItemsSave);
        //skinCareProdService.saveSCAP(scapBatchItemsSave);

        adminService.uploadAllToScp(scapBatchItemsSave)
            .then(function(data){
                var rawtoscp = data.data;
                //var toscpStat = rawtoscp.data;
                $rootScope.loader = false;
                alert(rawtoscp);
                //console.log(rawtoscp);
                // $scope.refresh();
            });
    };

    // $scope.toSCP = function(item) {
    //     $rootScope.loader = true;
    //     //var scapBatchItems = [];
    //     var scapBatchItemsSave = []
    //     var scpItemArray = [];
    //     angular.forEach($scope.rawSCP, function(item) {
            
    //         if (item.checked) 
    //         //console.log(item);
    //         scpItemArray.push(item.id);
    //     });
    //     var scapBatchItemsSave = scpItemArray;
    //     console.log(scapBatchItemsSave);
    //     //skinCareProdService.saveSCAP(scapBatchItemsSave);
        
    //     skinCareProdService.toSCP(scapBatchItemsSave)
    //         .then(function(data){
    //             var rawtoscp = data.data;
    //             //var toscpStat = rawtoscp.data;
    //             $rootScope.loader = false;
    //             alert(rawtoscp);
    //             //console.log(rawtoscp);
    //             $scope.refresh();
    //         });
        
        
    // };
    
});