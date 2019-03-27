if(!localStorage.username){
    window.location.href='login.html';
}else{
    document.getElementById("username").innerHTML = localStorage.getItem("username");
}

function renewCacheTime(){
	var currentDate = new Date();
	var currentTime = currentDate.getTime();
	localStorage.timeIn = currentTime;
}

function checkCacheTime(){
	var dateInstance = new Date();
	var timeInstance = dateInstance.getTime();
	var sessionTime = timeInstance - localStorage.timeIn;
	if(sessionTime > 3600000){ // expire session in 1 hr.
		logout();
		alert("Session expire. Please re-login.");
		window.location.href='login.html';
	}
}

if(!localStorage.timeIn){
	renewCacheTime();
}else{
	checkCacheTime();
}

setInterval(function(){
    checkCacheTime();
}, 300000); //session check every 5 mins