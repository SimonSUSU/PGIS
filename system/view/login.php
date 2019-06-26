<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?=$this->SYSTEM_CONFIG['webName']?></title>
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
<meta name="renderer" content="webkit">
<? $this->load_css(array('swiper.min','web')); ?>

<style media="screen">
.layui-layer-close{-webkit-app-region: no-drag;}
.layui-layer-btn0{-webkit-app-region: no-drag;background-color: #fff !important;border: 1px solid #dedede !important;color: #333 !important;}
.layui-layer-btn1{-webkit-app-region: no-drag;background-color: #1E9FFF !important;border: 1px solid #1E9FFF !important;color: #fff !important;}
/*#loginQcode{padding:20px 0;}
.panelContent{position: relative;}
.panelContent .info{position: absolute;left: 0;top: 0;right: 0;bottom: 0;background: #fff;z-index:9;}
.impowerBox .status{margin-top: 90px;}
.impowerBox .icon38_msg{display: block;margin: 0 auto;}
*/
.loginBox{width: 422px;}
</style>
</head>
<body>

<!--[if lt IE 10]>
<div class="ieCover">
  <h1>浏览器版本过低，请使用至少IE10或以上版本浏览器访问。</h1>
</div>
<![endif]-->


<div class="closeBtn hide" id="closeBtn">
  <span onclick="minimize()">-</span>
  <span onclick="closewin()">x</span>
</div>

<div id="bg"></div>

<div class="indexTitle"><?=$this->SYSTEM_CONFIG['webName']?></div>

<div class="swiper-container">
  <div class="swiper-pagination">
    <ul>
      <li class="swiper-pagination-bullet">账户登录</li>
    </ul>
  </div>
  <div class="swiper-wrapper">
    <div class="swiper-slide" data-hash="mp">

        <div class="loginBox">
          <form method="post">
          <p class="input-box"><input class="phone" type="number" name="phone" id="phone" placeholder="请输入请手机号" value="" oninput="if(value.length>11)value=value.slice(0,11)" /></p>
          <p class="input-box"><button type="button" id="getCode">获取验证码</button><input class="code" type="number" name="code" placeholder="请输入验证码" value="" oninput="if(value.length>4)value=value.slice(0,4)" /></p>
          <p><button class="btn" type="submit">登录</button></p>
          </form>
        </div>

    </div>
  </div>
</div>

 <div class="copyRight">
  <p>&copy; <?=date('Y')?> <?=$this->SYSTEM_CONFIG['webName']?>. All Rights Reserved.</p>
</div>


</body>
<? $this->load_js(array('jquery','layer/layer','swiper.jquery.min','jquery.particleground.min')); ?>
<script>
$(document).ready(function() {
  $('#bg').particleground({
    dotColor:'rgba(255,255,255,.1)',
    lineColor: 'rgba(255,255,255,.1)'
  });
});
var mySwiper = new Swiper('.swiper-container', {
    //loop: true,
    pagination: '.swiper-pagination',
    paginationClickable: true,
    spaceBetween: 0,
    centeredSlides: true,
    speed:200,
    //autoplay: 5000,
    //width: window.innerWidth,
    //height: window.innerHeight,
    //autoHeight: true, //高度随内容变化
    //hashnav:true,
    hashnavWatchState:false,
    simulateTouch : false,
});

// $(window).resize(function(){
//   setTimeout(location.reload(),'1000');
// });

$("#getCode").click(function(){
    var phone = $("#phone").val();
    if(phone===''){
        layer.alert('手机号码不能为空');
        return false;
    }
    $.ajax({
        url: '<?=url(array('home', 'getcode','login'))?>',
        type: 'POST',
        cache: false,
        data: {
            'phone':phone,
        },
        dataType:"json",
        success:function(data){
            //console.log(data);
            if(data.code == 'Success'){
              settime();
              layer.msg(data.msg);
            }else{
              layer.msg(data.msg);
            }
        },
        error:function(responseStr) {
            layer.msg('网络不稳定，请重试');
        }
    })
});

// 获取验证码倒计时
var countdown=60;
function settime(){
  if (countdown == 0){
            $('#getCode').removeAttr('disabled');
            $('#getCode').html('重新获取');
      countdown = 60;
      return;
  }else{
            $('#getCode').attr('disabled', 'disabled');
            $('#getCode').html(countdown+'s');
      countdown--;
  }
  setTimeout(function() {
      settime();
  },1000);
}
</script>
</html>