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
	$(".webMain").css("height",windowHeight+'px');
}
</script>