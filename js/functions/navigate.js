$(function() {
    $(".tabClicker").click(function() {
        var tab = $(this).attr("data-tab");
        $(".tabContent").hide();
        $("#" + tab).show();
    });
    $(".tabClickers").click(function() {
        var tab = $(this).attr("data-tab");
        $(".tabContent").hide();
        $("#" + tab).show();
    });

   // $(".nav a").on("click", function(){
   // $(".nav").find(".active").removeClass("active");
   // $(this).parent().addClass("active");

 //   	$(document).ready(function() {
 //  		$('li.active').removeClass('active');
 //  		$('a[href="' + location.pathname + '"]').closest('li').addClass('active'); 
	// });
	$(".nav a").on("click", function() {
  		$(".nav").find(".active").removeClass("active");
  		$(this).parent().addClass("active");
	});
});