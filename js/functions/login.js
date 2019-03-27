function logout(){
	localStorage.removeItem('username');
	localStorage.removeItem('usertype');
	localStorage.removeItem('member_info');
	localStorage.removeItem('attendance');
	localStorage.removeItem('schedule');
	localStorage.removeItem('salary');
	localStorage.removeItem('timeIn');
}
