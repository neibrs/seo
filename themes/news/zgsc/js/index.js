$(function(){
	$(".headerBox").load("include/header.html");
	$(".footerBox").load("include/footer.html");
})


// banner
$(function() {
	$("#slideTopBox").slide({
		mainCell: ".bd ul",
		autoPlay: true,
		interTime: 5000,
		delayTime: 1000,
		effect: "fold"
	});
});

// 新闻中心
$(function(){
	$("#slide_new").slide({
		mainCell: ".bd ul",
		autoPlay: true,
		interTime: 3000,
		delayTime: 800,
		effect: "fold"
	});
})
$(function(){
	$(".new_t span").on("click",function(){
		var index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(".tab_show_box .tab_show_item").eq(index).addClass("active").siblings().removeClass("active")
	})
})