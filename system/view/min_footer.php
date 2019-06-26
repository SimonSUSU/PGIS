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

access_log();
function access_log(){
	$.post('<?=url(array('home', 'access_log'))?>',
		{
		'url':'<?=$_SERVER['REQUEST_URI']?>',
		}
	)
}

var windowHeight=$(window).height();
sizeCost(windowHeight);
$(window).resize(function(){
   windowHeight=$(this).height();
   sizeCost(windowHeight);
});

function sizeCost(windowHeight){
	$(".tsovMain").css("height",(windowHeight-20)+'px');
}
</script>

<script>
var _hmt = _hmt || [];
_hmt.push(['_setCustomVar', 1, 'IP', '<?=IP();?>',1]);
_hmt.push(['_setCustomVar', 2, 'USER', '<?=!empty($this->user_id)?$this->user_id:0?>',1]);
_hmt.push(['_setCustomVar', 3, 'URL', '<?=$_SERVER['REQUEST_URI']?>?U=<?=!empty($this->user_id)?$this->user_id:0?>?T=<?=date('Y-m-d H:i:s')?>',1]);
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?d319707e4ab565d1b39d19cca433eabb";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
