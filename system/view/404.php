<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<meta name="renderer" content="webkit">
<title>找不到页面</title>
<style>
html{font:14px "Helvetica Neue",Helvetica,Arial,sans-serif; overflow: auto;}
body{font:14px "Helvetica Neue",Helvetica,Arial,sans-serif; background:#4caf50; line-height:165%; margin:0; padding:0; color:#474747;}
form,ul,ol,li,dl,dt,dd,form,img,p,h3,h6{margin:0; padding:0; border:none; list-style-type:none;}
a:link, a:visited{text-decoration:underline;color:#4caf50; font-size:180%;}
a:hover{color:#333; text-decoration:underline;}

.body404{margin:5% auto; width:80%; max-width:580px;  background:#fff; padding:20px;}
.body404 h3{font-size:300%; text-align: center; height:60px; line-height:60px; overflow:hidden; margin:20px 0;}
.body404 h2{font-size:800%; color:#4caf50; height:100px; line-height:100px; overflow:hidden; margin:10px 0; text-align:center;}
.body404 ul{}
.body404 ul dl{font-size:180%; margin:0 0 20px 0; text-align:center;}
.body404 ul p{color:#999;}
.body404 ul ol {margin:20px 0;}
.body404 ul ol h6{font-size:140%;}
</style>

</head> 
<body>

<div class="body404">
	<h2>404</h2>	
	<ul>
		<dl>您访问的页面不存在或已迁移！</dl>
		<dl><?=$_SERVER['REQUEST_URI'];?></dl>
		<ol>
			<p>首先对您造成的不便表示歉意。我们经过的分析和计算，认为页面不存在的原因可能如下：</p>
			<li>1、您访问的页面根本就不存在，请您检查您输入的地址是否正确；</li>
			<li>2、您访问的页面正在进行维护，请稍后再试试；</li>
			<li>3、服务器的访问量太大，请稍后再试试；</li>
			<li>4、本页面太长时间没有被访问过了；</li>
			<li>5、本页面已迁移！</li>
		</ol>
		<ol><p style="text-align:center;"><a href="/">回到首页</a></p></ol>
	</ul>
</div>
</body>
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
</html>