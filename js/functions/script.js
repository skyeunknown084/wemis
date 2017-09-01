//INSERT FUNCTIONS
function saveData(){
    var name = $('#nm').val();
    var email = $('#em').val();
    var phone = $('#hp').val();
    var address = $('#al').val();
    $.ajax({
        type: "POST",
        url: "server.php?p=add",
        data: "nm="+name+"&em="+email+"&hp="+phone+"&al="+address,
        success: function(data){
            // alert('Success insert data');
            viewData();
        }
    });
}

//GET LIST FUNCTIONS
function viewData(){
    $.ajax({
        type: "GET",
        url: "server.php",
        success: function(data){
            $('tbody').html(data);
        }
    });
}


//NAVIGATIONS
$(document).ready(function(){
        // $("#dash").show();
		// $("#meminfo").hide();
        $("#attend").hide();
        $("#sched").hide();
        $("#salary").hide();
        $("#usersett").hide();
        $("#account").hide();
		$("#privacy").hide();
    
    $("#p1").click(function(){
       // $("#dash").hide();
        $("#meminfo").show();
        $("#attend").hide();
        $("#sched").hide();
        $("#salary").hide();
        $("#usersett").hide();
        $("#account").hide();
        $("#privacy").hide();
    });
    $("#p2").click(function(){
        // $("#dash").hide();
        $("#meminfo").hide();
        $("#attend").show();
        $("#sched").hide();
        $("#salary").hide();
        $("#usersett").hide();
        $("#account").hide();
        $("#privacy").hide();
    });
    $("#p3").click(function(){
        // $("#dash").hide();
        $("#meminfo").hide();
        $("#attend").hide();
        $("#sched").show();
        $("#salary").hide();
        $("#usersett").hide();
        $("#account").hide();
        $("#privacy").hide();
    });
    $("#p4").click(function(){
        // $("#dash").hide();
        $("#meminfo").hide();
        $("#attend").hide();
        $("#sched").hide();
        $("#salary").show();
        $("#usersett").hide();
        $("#account").hide();
        $("#privacy").hide();
    });
    $("#p5").click(function(){
        // $("#dash").hide();
        $("#meminfo").hide();
        $("#attend").hide();
        $("#sched").hide();
        $("#salary").hide();
        $("#usersett").show();
        $("#account").hide();
        $("#privacy").hide();
    });
    $("#p6").click(function(){
        // $("#dash").hide();
        $("#meminfo").hide();
        $("#attend").hide();
        $("#sched").hide();
        $("#salary").hide();
        $("#usersett").hide();
        $("#account").show();
        $("#privacy").hide();
    });
    $("#p7").click(function(){
        // $("#dash").hide();
        $("#meminfo").hide();
        $("#attend").hide();
        $("#sched").hide();
        $("#salary").hide();
        $("#usersett").hide();
        $("#account").hide();
        $("#privacy").show();
    });
});