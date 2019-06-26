</div>
</body>
<? $this->load_js(array('jquery','layer/layer','DatePicker/WdatePicker','jquery.easyui','easyui-lang-zh_CN')); ?>
<script type="text/javascript">
function openWin(url,width,height,title,endReload){
	layer.open({
	  type: 2,
	  title: title,
	  area: [width,height],
	  content: url,
	  end: function(){
			if(endReload==1){
        		parent.location.reload();
        	}
        }
	});
}

sizeCost();
$(window).resize(function(){
   sizeCost();
});

function sizeCost(){
	var windowHeight=$(window).height();
	var headerHeight=$(".header").height();
	$(".webMain").css("height",(windowHeight-headerHeight-20)+'px');

	var window_Width=$(window).width();
	var header_Logo_Width=$('.header .logo').width();
	var header_ol_Width = $('.header ol').width();
	var dl_num = $('.header dl').children().length;
	var dl_max_Width = window_Width-header_Logo_Width-header_ol_Width-25;
	var dl_Width = dl_num*95;
	//console.log("dl_max_Width:"+dl_max_Width+",dl_Width:"+dl_Width+",dlnum:"+dl_num);
	if(dl_max_Width < dl_Width ){
		header_dl_a_width = dl_max_Width / dl_num -5;
		if(header_dl_a_width<70){
			header_dl_a_width = 45;
		}
		$(".header dl a").css("width", header_dl_a_width+'px');
		$(".header dl").css("width", dl_max_Width+'px');
	}
	$(".header dl").css('height','60px');
	$(".header dl a").css('font-size','16px');
}
</script>